<?php

namespace App\Http\Controllers\api\v2\Admin\Payment;

use App\User;
use App\SmSchool;
use App\SmStudent;
use Carbon\Carbon;
use App\SmAddIncome;
use App\SmBankAccount;
use App\SmAcademicYear;
use App\SmBankStatement;
use App\SmPaymentMethhod;
use App\Scopes\SchoolScope;
use Illuminate\Http\Request;
use App\Scopes\AcademicSchoolScope;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Scopes\ActiveStatusSchoolScope;
use Modules\Fees\Entities\FmFeesInvoice;
use Modules\Fees\Entities\FmFeesTransaction;
use Modules\Fees\Entities\FmFeesInvoiceChield;
use Modules\Wallet\Entities\WalletTransaction;
use Modules\Fees\Entities\FmFeesTransactionChield;
use Modules\Fees\Http\Controllers\FeesExtendedController;
use App\Http\Resources\v2\Admin\BankPayment\BankPaymentListResourse;

class BankPaymentController extends Controller
{
    public function paymentList(Request $request)
    {
        $request->merge([
            'start_date'    => Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d'),
            'end_date'      => Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d')
        ]);

        $feesPayments = FmFeesTransaction::withoutGlobalScope(AcademicSchoolScope::class)->whereIn('payment_method', ['Bank', 'Cheque'])
            ->where('school_id', auth()->user()->school_id)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->when($request->class_id, function ($query) use ($request) {
                $query->whereHas('recordDetail', function ($q) use ($request) {
                    return $q->where('class_id', $request->class_id);
                });
            })
            ->when($request->section_id, function ($query) use ($request) {
                $this->validate($request, ['class_id' => 'required_with:section_id']);
                $query->whereHas('recordDetail', function ($q) use ($request) {
                    return $q->where('section_id', $request->section_id);
                });
            })
            ->whereDate('created_at',  '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date);

        $paymentData = BankPaymentListResourse::collection($feesPayments->get())->groupBy('paid_status');

        $statuses = ['approve', 'pending', 'reject'];
        $statusHint = null;
        foreach ($statuses as $status) {
            $statusHint .= strtoupper($status) . ': ' . strtolower($status).', ';
            $data[$status] = $paymentData[$status] ?? [];
        }
        $data['status'] = (string)rtrim($statusHint,', ');

        // $data['approve']    = $data['approve'] ?? [];
        // $data['pending']    = $data['pending'] ?? [];
        // $data['reject']     = $data['reject'] ?? [];
        // $data['status']     = 'PENDING: pending, APPROVE: approve, REJECT: reject';

        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Bank payment list'
            ];
        }
        return response()->json($response);
    }

    public function changePaymentStatus(Request $request)
    {
        // Request => change_status = approve || reject, transcation_id, paid_amount = nullable
        $this->validate($request, [
            'change_status' => 'required|in:approve,reject',
            'transaction_id' => 'required|exists:fm_fees_transactions,id',
            'paid_amount' => 'nullable|numeric'
        ], [
            'change_status.in' => "The change status should be 'approve' or 'reject'."
        ]);

        switch ($request->change_status) {
            case 'approve':
                $transcation = $request->transaction_id;
                if ($request->paid_amount) {
                    $total_paid_amount = $request->paid_amount;
                } else {
                    $total_paid_amount = null;
                }
                $transcationInfo = FmFeesTransaction::withoutGlobalScope(AcademicSchoolScope::class)
                    ->where('school_id', auth()->user()->school_id)
                    ->where('id', $transcation)->first();
                $fees_transcation = $this->addFeesAmount($transcation, $total_paid_amount);

                $student = SmStudent::withoutGlobalScope(SchoolScope::class)->with('parents')
                    ->where('school_id', auth()->user()->school_id)
                    ->where('id', $transcationInfo->student_id)->first();
                sendNotification("Fees_Payment", null, 1, 1);
                sendNotification("Fees_Payment", null, $student->user_id, 2);
                sendNotification("Fees_Payment", null, $student->parents->user_id, 3);
                break;
            case 'reject':
                $fees_transcation = FmFeesTransaction::withoutGlobalScope(AcademicSchoolScope::class)
                    ->where('school_id', auth()->user()->school_id)
                    ->where('id', $request->transaction_id)->first();
                $fees_transcation->paid_status = 'reject';
                $fees_transcation->update();

                $student = SmStudent::withoutGlobalScope(SchoolScope::class)->with('parents')
                    ->where('school_id', auth()->user()->school_id)
                    ->where('id', $fees_transcation->student_id)->first();
                sendNotification("Fees_Payment", null, 1, 1);
                sendNotification("Fees_Payment", null, $student->user_id, 2);
                sendNotification("Fees_Payment", null, $student->parents->user_id, 3);
                break;
        }

        if (!$fees_transcation) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'Payment status changed successfully'
            ];
        }
        return response()->json($response);
    }


    private function addFeesAmount($transcation_id, $total_paid_amount)
    {
        $transcation = FmFeesTransaction::withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id)->where('id', $transcation_id)->first();
        $fees_invoice = FmFeesInvoice::withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id)->where('id', $transcation->fees_invoice_id)->first();
        $allTranscations = FmFeesTransactionChield::withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id)->where('fees_transaction_id', $transcation->id)->get();

        foreach ($allTranscations as $key => $allTranscation) {
            $transcationId = FmFeesTransaction::withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id)->where('id', $allTranscation->fees_transaction_id)->first();
            $fesInvoiceId = FmFeesInvoiceChield::withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id)->where('fees_invoice_id', $transcationId->fees_invoice_id)
                ->where('fees_type', $allTranscation->fees_type)
                ->first();

            $storeFeesInvoiceChield = FmFeesInvoiceChield::find($fesInvoiceId->id);
            $storeFeesInvoiceChield->due_amount = $storeFeesInvoiceChield->due_amount - $allTranscation->paid_amount;
            $storeFeesInvoiceChield->paid_amount = $storeFeesInvoiceChield->paid_amount + $allTranscation->paid_amount;
            $storeFeesInvoiceChield->service_charge = chargeAmount($transcation->payment_method, $allTranscation->paid_amount);
            $storeFeesInvoiceChield->update();

            // Income
            $payment_method = SmPaymentMethhod::withoutGlobalScope(ActiveStatusSchoolScope::class)->where('school_id', auth()->user()->school_id)->where('method', $transcation->payment_method)->first();
            $income_head = generalSetting();

            $add_income = new SmAddIncome();
            $add_income->name = 'Fees Collect';
            $add_income->date = date('Y-m-d');
            $add_income->amount = $allTranscation->paid_amount;
            $add_income->fees_collection_id = $transcation->fees_invoice_id;
            $add_income->active_status = 1;
            $add_income->income_head_id = $income_head->income_head_id;
            $add_income->payment_method_id = $payment_method->id;
            if ($payment_method->id == 3) {
                $add_income->account_id = $transcation->bank_id;
            }
            $add_income->created_by = Auth()->user()->id;
            $add_income->school_id = auth()->user()->school_id;
            $add_income->academic_id = getAcademicId();
            $add_income->save();

            if ($transcation->payment_method == "Bank") {
                $bank = SmBankAccount::withoutGlobalScope(ActiveStatusSchoolScope::class)->where('id', $transcation->bank_id)
                    ->where('school_id', auth()->user()->school_id)
                    ->first();

                $after_balance = $bank->current_balance + $total_paid_amount;

                $bank_statement = new SmBankStatement();
                $bank_statement->amount = $allTranscation->paid_amount;
                $bank_statement->after_balance = $after_balance;
                $bank_statement->type = 1;
                $bank_statement->details = "Fees Payment";
                $bank_statement->payment_date = date('Y-m-d');
                $bank_statement->item_sell_id = $transcation->id;
                $bank_statement->bank_id = $transcation->bank_id;
                $bank_statement->school_id = auth()->user()->school_id;
                $bank_statement->payment_method = $payment_method->id;
                $bank_statement->save();

                $current_balance = SmBankAccount::withoutGlobalScope(ActiveStatusSchoolScope::class)->where('id', $transcation->bank_id)->where('school_id', auth()->user()->school_id)->first();
                $current_balance->current_balance = $after_balance;
                $current_balance->update();
            }
            $fees_transcation = FmFeesTransaction::withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id)->where('id', $transcation->id)->first();
            $fees_transcation->paid_status = 'approve';
            $fees_transcation->update();
        }



        if ($fees_invoice) {
            $balance = ($fees_invoice->Tamount + $fees_invoice->Tfine) - ($fees_invoice->Tpaidamount + $fees_invoice->Tweaver);
            if ($balance == 0) {
                $fees_invoice->payment_status = "paid";
                $fees_invoice->update();
                Cache::forget('have_due_fees_' . $transcation->user_id);
            } else {
                $fees_invoice->payment_status = "partial";
                $fees_invoice->update();
            }
        }

        if ($transcation->add_wallet_money > 0) {
            $user = User::where('school_id', auth()->user()->school_id)->where('id', $transcation->user_id)->first();
            $walletBalance = $user->wallet_balance;
            $user->wallet_balance = $walletBalance + $transcation->add_wallet_money;
            $user->update();

            $addPayment = new WalletTransaction();
            $addPayment->amount = $transcation->add_wallet_money;
            $addPayment->payment_method = $transcation->payment_method;
            $addPayment->user_id = $user->id;
            $addPayment->type = 'diposit';
            $addPayment->status = 'approve';
            $addPayment->note = 'Fees Extra Payment Add';
            $addPayment->school_id = auth()->user()->school_id;
            $addPayment->academic_id = getAcademicId();
            $addPayment->save();



            $school = SmSchool::find($user->school_id);
            $compact['full_name'] = $user->full_name;
            $compact['method'] = $transcation->payment_method;
            $compact['create_date'] = date('Y-m-d');
            $compact['school_name'] = $school->school_name;
            $compact['current_balance'] = $user->wallet_balance;
            $compact['add_balance'] = $transcation->add_wallet_money;
            $compact['previous_balance'] = $user->wallet_balance - $transcation->add_wallet_money;

            @send_mail($user->email, $user->full_name, "fees_extra_amount_add", $compact);

            sendNotification($user->id, null, null, $user->role_id, "Fees Xtra Amount Add");
        }

        return true;
    }
}
