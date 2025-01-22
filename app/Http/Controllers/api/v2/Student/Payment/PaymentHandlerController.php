<?php

namespace App\Http\Controllers\api\v2\Student\Payment;

use App\SmAddIncome;
use App\SmAcademicYear;
use App\SmPaymentMethhod;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Scopes\AcademicSchoolScope;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use App\Scopes\ActiveStatusSchoolScope;
use Modules\Fees\Entities\FmFeesInvoice;
use Modules\Fees\Entities\FmFeesTransaction;
use Modules\Fees\Entities\FmFeesInvoiceChield;
use Modules\Wallet\Entities\WalletTransaction;

class PaymentHandlerController extends Controller
{
    // request = amount,payment_method,fees_invoice_id,type
    public function handlePayment(Request $request)
    {
        if ($request->type == 'walletAddBallence') {
            $addPayment = new WalletTransaction();
            $addPayment->amount = $request->amount;
            $addPayment->payment_method = $request->payment_method;
            $addPayment->user_id = auth()->user()->id;
            $addPayment->type = 'diposit';
            $addPayment->status = 'approve';
            $addPayment->school_id = auth()->user()->school_id;
            $addPayment->academic_id = SmAcademicYear::API_ACADEMIC_YEAR(auth()->user()->school_id);
            $result = $addPayment->save();

            if ($result) {
                $user = User::where('school_id', auth()->user()->school_id)->find($addPayment->user_id);
                $currentBalance = $user->wallet_balance;
                $user->wallet_balance = $currentBalance + $addPayment->amount;
                $user->update();
                $gs = generalSetting();
                $compact['full_name'] =  $user->full_name;
                $compact['method'] =  $addPayment->payment_method;
                $compact['create_date'] =  date('Y-m-d');
                $compact['school_name'] =  $gs->school_name;
                $compact['current_balance'] =  $user->wallet_balance;
                $compact['add_balance'] =  $addPayment->amount;

                @send_mail($user->email, $user->full_name, "wallet_approve", $compact);

                $paymentMethod = SmPaymentMethhod::withoutGlobalScope(ActiveStatusSchoolScope::class)
                    ->where('school_id', auth()->user()->school_id)
                    ->find($addPayment->payment_method);

                $data = [
                    'add_amount' => (float)$addPayment->amount,
                    'add_method' => (string)$paymentMethod->method,
                    'add_status' => (string)$addPayment->status,
                    'add_type'   => (string)$addPayment->type,
                ];

                $response = [
                    'success' => true,
                    'data' => $data,
                    'message' => 'Wallet Ballance Added Successfully.'
                ];
            }
        } elseif ($request->type == 'feesInvoice') {
            $invoice = FmFeesInvoice::withoutGlobalScope(AcademicSchoolScope::class)
                ->where('school_id', auth()->user()->school_id)
                ->find($request->fees_invoice_id);

            if ($invoice) {
                $record = StudentRecord::where('student_id', $invoice->student_id)
                    ->where('school_id', auth()->user()->school_id)
                    ->where('class_id', $invoice->class_id)
                    ->first();

                if ($record) {
                    $storeTransaction = new FmFeesTransaction();
                    $storeTransaction->fees_invoice_id  = $request->fees_invoice_id;
                    $storeTransaction->payment_method   = $request->payment_method;
                    $storeTransaction->student_id       = auth()->user()->school_id;
                    $storeTransaction->record_id        = $record->id;
                    $storeTransaction->user_id          = auth()->user()->id;
                    $storeTransaction->paid_status      = 'approve';
                    $storeTransaction->school_id        = auth()->user()->school_id;
                    $storeTransaction->academic_id      = SmAcademicYear::API_ACADEMIC_YEAR(auth()->user()->school_id);
                    $storeTransaction->save();

                  
                    $feesInvoiceChilds = FmFeesInvoiceChield::where('fees_invoice_id', $invoice->id)
                        ->where('school_id', auth()->user()->school_id)
                        ->get(); 

                    foreach ($feesInvoiceChilds as $feesInvoiceChild) {
                        #$storeTransactionChield = new FmFeesTransactionChield();
                        $storeTransactionChield = FmFeesInvoiceChield::where('fees_invoice_id', $request->fees_invoice_id)
                            ->where('fees_type', $feesInvoiceChild->fees_type)
                            ->first();
                        #$storeTransactionChield->fees_transaction_id = $storeTransaction->id;
                        #$storeTransactionChield->fees_type = $feesInvoiceChild->fees_type;
                        $storeTransactionChield->due_amount = $feesInvoiceChild->due_amount - $request->amount;
                        $storeTransactionChield->paid_amount = $feesInvoiceChild->paid_amount + $request->amount;
                        $storeTransactionChield->school_id = auth()->user()->school_id;
                        $storeTransactionChield->academic_id = SmAcademicYear::API_ACADEMIC_YEAR(auth()->user()->school_id);
                        $storeTransactionChield->save();

                        // $transcationId = FmFeesTransaction::find($storeTransactionChield->fees_transaction_id);
                        
                        // $fesInvoiceId = FmFeesInvoiceChield::where('fees_invoice_id', $transcationId->fees_invoice_id)
                        //     ->where('fees_type', $feesInvoiceChild->fees_type)
                        //     ->first();
            
                        // $storeFeesInvoiceChield = FmFeesInvoiceChield::find($fesInvoiceId->id);
                        // $storeFeesInvoiceChield->due_amount = $storeFeesInvoiceChield->due_amount - $feesInvoiceChild->paid_amount;
                        // $storeFeesInvoiceChield->paid_amount = $storeFeesInvoiceChield->paid_amount + $feesInvoiceChild->paid_amount;
                        #$storeFeesInvoiceChield->service_charge = chargeAmount($transcation->payment_method, $feesInvoiceChild->paid_amount);
                        #$storeFeesInvoiceChield->update();

                        #$feesInvoiceChild->paid_amount = $request->amount;
                        #$feesInvoiceChild->due_amount = 0;
                        $feesInvoiceChild->save();
                    }

                    $income_head = SmGeneralSettings::where('school_id', auth()->user()->school_id)->first('income_head_id');

                    $add_income = new SmAddIncome();
                    $add_income->name = 'Fees Collect';
                    $add_income->date = date('Y-m-d');
                    $add_income->amount = $request->amount;
                    $add_income->fees_collection_id = $storeTransaction->fees_invoice_id;
                    $add_income->active_status = 1;
                    $add_income->income_head_id = $income_head->income_head_id;
                    $add_income->payment_method_id = $request->payment_method;
                    $add_income->created_by = Auth()->user()->id;
                    $add_income->school_id = auth()->user()->school_id;
                    $add_income->academic_id = SmAcademicYear::API_ACADEMIC_YEAR(auth()->user()->school_id);
                    $add_income->save();

                    $paymentMethod = SmPaymentMethhod::withoutGlobalScope(ActiveStatusSchoolScope::class)
                        ->where('school_id', auth()->user()->school_id)
                        ->find($request->payment_method);

                    $invoice->payment_status = 'approved';
                    $invoice->payment_method = $paymentMethod->method;
                    $invoice->save();

                    $data = [
                        'paid_amount' => (float)$request->amount,
                        'paid_method' => (string)$paymentMethod->method,
                        'paid_status' => (string)$invoice->payment_status
                    ];

                    $response = [
                        'success' => true,
                        'data' => $data,
                        'message' => 'Fees Payment Successfully.'
                    ];
                }
            }
        }

        return response()->json($response, 200);
    }
}