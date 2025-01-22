<?php

namespace App\Http\Controllers\api\v2\Fees;

use App\SmClass;
use App\SmSchool;
use App\SmStudent;
use App\Models\User;
use App\SmAddIncome;
use App\SmFeesAssign;
use App\SmBankAccount;
use App\SmFeesPayment;
use App\SmAcademicYear;
use App\SmPaymentMethhod;
use App\SmBankPaymentSlip;
use App\SmGeneralSettings;
use App\Models\FeesInvoice;
use App\Scopes\SchoolScope;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmFeesAssignDiscount;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use App\SmPaymentGatewaySetting;
use Illuminate\Support\Facades\DB;
use App\Scopes\AcademicSchoolScope;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Fees\Entities\FmFeesType;
use Modules\Fees\Entities\FmFeesGroup;
use App\Scopes\ActiveStatusSchoolScope;
use Modules\Fees\Entities\FmFeesInvoice;
use Illuminate\Support\Facades\Validator;
use App\Models\DirectFeesInstallmentAssign;
use Modules\Fees\Entities\FmFeesTransaction;
use App\Http\Resources\FmFeesInvoiceResource;
use Modules\Fees\Entities\FmFeesInvoiceChield;
use Modules\Wallet\Entities\WalletTransaction;
use App\Models\DireFeesInstallmentChildPayment;
use App\Http\Resources\FmFeesInvoiceAddResource;
use App\Http\Resources\FmFeesInvoiceViewResource;
use Modules\Fees\Entities\FmFeesTransactionChield;
use App\Http\Resources\v2\FeesInstallmentListResource;
use App\Http\Resources\FmFeesInvoiceChieldViewResource;
use Modules\University\Entities\UnFeesInstallmentAssign;
use Modules\CcAveune\Http\Controllers\CcAveuneController;
use Modules\Fees\Http\Controllers\FeesExtendedController;
use Modules\ToyyibPay\Http\Controllers\ToyyibPayController;
use Modules\University\Entities\UnFeesInstallAssignChildPayment;

class StudentFeesController extends Controller
{
    public function studentFeesList(Request $request)
    {
        $feesPaymentType = SmGeneralSettings::where('school_id', auth()->user()->school_id)
            ->select('fees_status as new_fees')
            ->first();

        $feesPaymentType->makeHidden('currencyDetail');

        $data['new_fees'] = $feesPaymentType->new_fees == 1 ? true : false;
        $student = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->student_id)
            ->firstOrFail();

        $user = User::where('school_id', auth()->user()->school_id)->where('id', $student->user_id)->first();

        if ($user->role_id != 2) {
            $response = [
                'status'  => false,
                'data' => 'No data found',
                'message' => 'Operation failed',
            ];
            return response()->json($response, 401);
        }
        $records = FmFeesInvoice::withoutGlobalScopes([AcademicSchoolScope::class])
            ->where('record_id', $request->record_id)
            ->where('student_id', $request->student_id)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)
            ->with('studentInfo', 'recordDetail.class', 'recordDetail.section')
            ->orderBy('id', 'DESC')
            ->get();

        $data['fees_invoice'] = FmFeesInvoiceResource::collection($records);

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
                'message' => 'Fees invoice list'
            ];
        }

        return response()->json($response);
    }

    public function addFeesPayment(Request $request)
    {
        $paymentMethods = SmPaymentMethhod::withoutGlobalScope(ActiveStatusSchoolScope::class)
            ->whereNotIn('method', ["Cash"])
            ->where('school_id', auth()->user()->school_id)
            ->select('id', 'method');

        if (!moduleStatusCheck('RazorPay')) {
            $paymentMethods = $paymentMethods->whereNot('method', 'RazorPay');
        }

        $data['paymentMethods'] = $paymentMethods->get();

        $data['bankAccounts'] = SmBankAccount::withoutGlobalScope(ActiveStatusSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('active_status', 1)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->get()->map(function ($value) {
                return [
                    'id'                => (int)$value->id,
                    'bank_name'         => (string)$value->bank_name . '-' . $value->account_number,
                    'account_number'    => (string)$value->account_number
                ];
            });
        $invoiceInfo = FmFeesInvoice::withoutGlobalScopes([AcademicSchoolScope::class])
            ->with('recordDetail', 'studentInfo')
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->fees_invoice_id)->first();

        $data['invoiceInfo'] = new FmFeesInvoiceAddResource($invoiceInfo);

        $data['invoiceDetails'] = FmFeesInvoiceChield::select('fees_type', 'amount', 'due_amount', 'weaver', 'fine')
            ->with(['feesType' => function ($q) {
                $q->withoutGlobalScope(AcademicSchoolScope::class)->where('school_id', auth()->user()->school_id)->select('id', 'name');
            }])->where('fees_invoice_id', $data['invoiceInfo']->id)
            ->where('school_id', auth()->user()->school_id)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->get();

        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed',
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Add fees payment detail',
            ];
        }

        return response()->json($response);
    }

    public function serviceCharge(Request $request)
    {
        $data['service_charge']         = (string)serviceCharge($request->gateway);
        $data['service_charge_amount']  =  (float)number_format(chargeAmount($request->gateway, $request->amount), 2, '.', '');

        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed',
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Service charge',
            ];
        }

        return response()->json($response);
    }

    public function studentFeesPaymentStore(Request $request)
    {
        $this->validate($request, [
            'total_paid_amount' => 'required',
            'payment_method'    => 'required',
            'bank'              => 'nullable|required_if:payment_method,Bank',
        ], [
            'total_paid_amount.required' => 'Paid amount cannot be blank',
        ]);

        $destination = 'public/uploads/student/document/';
        $file = fileUpload($request->file('file'), $destination);

        $record = StudentRecord::where('school_id', auth()->user()->school_id)
            ->where('id', $request->student_id)
            ->first();

        $student = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->with(['parents' => function ($q) {
                $q->withoutGlobalScope(SchoolScope::class)->where('school_id', auth()->user()->school_id);
            }])
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $record->student_id)
            ->first();

        if ($request->payment_method == "Wallet") {
            $user = User::find(auth()->user()->id);
            if ($user->wallet_balance == 0) {
                $response = [
                    'status'  => false,
                    'data' => 'Insufficiant Balance',
                    'message' => 'Insufficiant Balance',
                ];
                return response()->json($response, 401);
            } elseif ($user->wallet_balance >= $request->total_paid_amount) {
                $user->wallet_balance = $user->wallet_balance - $request->total_paid_amount;
                $user->update();
            } else {
                $response = [
                    'status'  => false,
                    'data' => 'Total Amount Is Grater Than Wallet Amount',
                    'message' => 'Total Amount Is Grater Than Wallet Amount',
                ];
                return response()->json($response, 401);
            }
            $addPayment = new WalletTransaction();
            if ($request->add_wallet > 0) {
                $addAmount = $request->total_paid_amount - $request->add_wallet;
                $addPayment->amount = $addAmount;
            } else {
                $addPayment->amount = $request->total_paid_amount;
            }
            $addPayment->payment_method = $request->payment_method;
            $addPayment->user_id = $user->id;
            $addPayment->type = 'expense';
            $addPayment->status = 'approve';
            $addPayment->note = 'Fees Payment';
            $addPayment->school_id = auth()->user()->school_id;
            $addPayment->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            $addPayment->save();

            $storeTransaction = new FmFeesTransaction();
            $storeTransaction->fees_invoice_id = $request->invoice_id;
            $storeTransaction->payment_note = $request->payment_note;
            $storeTransaction->payment_method = $request->payment_method;
            $storeTransaction->add_wallet_money = $request->add_wallet;
            $storeTransaction->bank_id = $request->bank;
            $storeTransaction->student_id = $record->student_id;
            $storeTransaction->record_id = $record->id;
            $storeTransaction->user_id = auth()->user()->id;
            $storeTransaction->file = $file;
            $storeTransaction->paid_status = 'approve';
            $storeTransaction->school_id = auth()->user()->school_id;
            if (moduleStatusCheck('University')) {
                $storeTransaction->un_academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            } else {
                $storeTransaction->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            }
            $storeTransaction->save();

            foreach ($request->fees_type as $key => $type) {
                $id = FmFeesInvoiceChield::where('school_id', auth()->user()->school_id)
                    ->where('fees_invoice_id', $request->invoice_id)
                    ->where('fees_type', $type)
                    ->first('id')->id;

                $storeFeesInvoiceChield = FmFeesInvoiceChield::find($id);
                $storeFeesInvoiceChield->due_amount = $request->due[$key];
                $storeFeesInvoiceChield->paid_amount = $storeFeesInvoiceChield->paid_amount + $request->paid_amount[$key] - $request->extraAmount[$key];
                $storeFeesInvoiceChield->update();

                if ($request->paid_amount[$key] > 0) {
                    $storeTransactionChield = new FmFeesTransactionChield();
                    $storeTransactionChield->fees_transaction_id = $storeTransaction->id;
                    $storeTransactionChield->fees_type = $type;
                    $storeTransactionChield->paid_amount = $request->paid_amount[$key] - $request->extraAmount[$key];
                    $storeTransactionChield->note = $request->note[$key];
                    $storeTransactionChield->school_id = auth()->user()->school_id;
                    if (moduleStatusCheck('University')) {
                        $storeTransactionChield->un_academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                    } else {
                        $storeTransactionChield->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                    }
                    $storeTransactionChield->save();
                }
            }

            if ($request->add_wallet > 0) {
                $user->wallet_balance = $user->wallet_balance + $request->add_wallet;
                $user->update();

                $addPayment = new WalletTransaction();
                $addPayment->amount = $request->add_wallet;
                $addPayment->payment_method = $request->payment_method;
                $addPayment->user_id = $user->id;
                $addPayment->type = 'diposit';
                $addPayment->status = 'approve';
                $addPayment->note = 'Fees Extra Payment Add';
                $addPayment->school_id = auth()->user()->school_id;
                $addPayment->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                $addPayment->save();

                $school = SmSchool::find($user->school_id);
                $compact['full_name'] = $user->full_name;
                $compact['method'] = $request->payment_method;
                $compact['create_date'] = date('Y-m-d');
                $compact['school_name'] = $school->school_name;
                $compact['current_balance'] = $user->wallet_balance;
                $compact['add_balance'] = $request->add_wallet;
                $compact['previous_balance'] = $user->wallet_balance - $request->add_wallet;

                @send_mail($user->email, $user->full_name, "fees_extra_amount_add", $compact);
                sendNotification("Fees Xtra Amount Add", null, $user->id, $user->role_id);
            }

            // Income
            $payment_method = SmPaymentMethhod::withoutGlobalScope(ActiveStatusSchoolScope::class)
                ->where('method', $request->payment_method)
                ->where('school_id', auth()->user()->school_id)
                ->first();
            $income_head = generalSetting();

            $add_income = new SmAddIncome();
            $add_income->name = 'Fees Collect';
            $add_income->date = date('Y-m-d');
            $add_income->amount = $request->total_paid_amount;
            $add_income->fees_collection_id = $storeTransaction->id;
            $add_income->active_status = 1;
            $add_income->income_head_id = $income_head->income_head_id;
            $add_income->payment_method_id = $payment_method->id;
            $add_income->created_by = Auth()->user()->id;
            $add_income->school_id = auth()->user()->school_id;
            $add_income->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            $add_income->save();
        } elseif ($request->payment_method == "Cheque" || $request->payment_method == "Bank" || $request->payment_method == "MercadoPago") {
            $storeTransaction = new FmFeesTransaction();
            $storeTransaction->fees_invoice_id = $request->invoice_id;
            $storeTransaction->payment_note = $request->payment_note;
            $storeTransaction->payment_method = $request->payment_method;
            $storeTransaction->add_wallet_money = $request->add_wallet;
            $storeTransaction->bank_id = $request->bank;
            $storeTransaction->student_id = $record->student_id;
            $storeTransaction->record_id = $record->id;
            $storeTransaction->user_id = auth()->user()->id;
            $storeTransaction->file = $file;
            $storeTransaction->paid_status = 'pending';
            $storeTransaction->school_id = auth()->user()->school_id;
            if (moduleStatusCheck('University')) {
                $storeTransaction->un_academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            } else {
                $storeTransaction->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            }
            $storeTransaction->save();

            foreach ($request->input('fees_type') as $key => $type) {
                if (isset($request->paid_amount[$key]) && $request->paid_amount[$key] > 0) {
                    $storeTransactionChield = new FmFeesTransactionChield();
                    $storeTransactionChield->fees_transaction_id = $storeTransaction->id;
                    $storeTransactionChield->fees_type = $type;
                    $storeTransactionChield->paid_amount = $request->paid_amount[$key] - $request->extraAmount[$key];
                    $storeTransactionChield->service_charge = chargeAmount($request->payment_method, $request->paid_amount[$key]);
                    $storeTransactionChield->note = $request->note[$key];
                    $storeTransactionChield->school_id = auth()->user()->school_id;

                    if (moduleStatusCheck('University')) {
                        $storeTransactionChield->un_academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                    } else {
                        $storeTransactionChield->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                    }
                    $storeTransactionChield->save();
                }
            }

            if (moduleStatusCheck('MercadoPago')) {
                if (@$request->payment_method == "MercadoPago") {
                    $storeTransaction->total_paid_amount = $request->total_paid_amount;
                    $storeTransaction->save();
                    return redirect()->route('mercadopago.mercadopago-fees-payment', ['traxId' => $storeTransaction->id]);
                }
            }
        } else {
            $storeTransaction = new FmFeesTransaction();
            $storeTransaction->fees_invoice_id = $request->invoice_id;
            $storeTransaction->payment_note = $request->payment_note;
            $storeTransaction->payment_method = $request->payment_method;
            $storeTransaction->student_id = $record->student_id;
            $storeTransaction->record_id = $record->id;
            $storeTransaction->add_wallet_money = $request->add_wallet;
            $storeTransaction->user_id = auth()->user()->id;
            $storeTransaction->paid_status = 'pending';
            $storeTransaction->school_id = auth()->user()->school_id;
            if (moduleStatusCheck('University')) {
                $storeTransaction->un_academic_id = getAcademicId();
            } else {
                $storeTransaction->academic_id = getAcademicId();
            }
            $storeTransaction->save();


            foreach ($request->fees_type as $key => $type) {
                if ($request->paid_amount[$key] > 0) {
                    $storeTransactionChield = new FmFeesTransactionChield();
                    $storeTransactionChield->fees_transaction_id = $storeTransaction->id;
                    $storeTransactionChield->fees_type = $type;
                    $storeTransactionChield->paid_amount = $request->paid_amount[$key] - $request->extraAmount[$key];
                    $storeTransactionChield->service_charge = chargeAmount($request->payment_method, $request->paid_amount[$key]);
                    $storeTransactionChield->note = $request->note[$key];
                    $storeTransactionChield->school_id = Auth::user()->school_id;
                    if (moduleStatusCheck('University')) {
                        $storeTransactionChield->un_academic_id = getAcademicId();
                    } else {
                        $storeTransactionChield->academic_id = getAcademicId();
                    }
                    $storeTransactionChield->save();
                }
            }

            $data = [];
            $data['invoice_id'] = $request->invoice_id;
            $data['amount'] = $request->total_paid_amount;
            $data['payment_method'] = $request->payment_method;
            $data['description'] = "Fees Payment";
            $data['type'] = "Fees";
            $data['student_id'] = $request->student_id;
            $data['user_id'] = $storeTransaction->user_id;
            $data['stripeToken'] = $request->stripeToken;
            $data['transcationId'] = $storeTransaction->id;
            $data['service_charge'] = chargeAmount($request->payment_method, $request->total_paid_amount);

            if ($data['payment_method'] == 'RazorPay') {
                $extendedController = new FeesExtendedController();
                $extendedController->addFeesAmount($storeTransaction->id, null);
            } elseif ($data['payment_method'] == 'CcAveune') {
                $ccAvenewPaymentController = new CcAveuneController();
                $ccAvenewPaymentController->studentFeesPay($data['amount'], $data['transcationId'], $data['type']);
            } elseif ($data['payment_method'] == 'ToyyibPay') {
                if (moduleStatusCheck('ToyyibPay')) {
                    $toyyibPayController = new ToyyibPayController();
                    $data = [
                        'amount' => $request->total_paid_amount,
                        'transcationId' => $storeTransaction->id,
                        'type' => 'Fees',
                        'student_id' => $request->student_id,
                        'user_id' => $storeTransaction->user_id,
                        'service_charge' => chargeAmount($request->payment_method, $request->total_paid_amount),
                        'invoice_id' => $request->invoice_id,
                        'payment_method' => $request->payment_method,
                        // 'invoice_id' => $request->invoice_id

                    ];
                    $data_store = $toyyibPayController->studentFeesPay($data);
                    // return redirect($data_store);
                }
            } else {
                $feesExtendedController = new FeesExtendedController();
                $feesExtendedController->addFeesAmount($storeTransaction->id, $request->total_paid_amount);
            }
        }

        //Notification
        try {
            sendNotification("Add Fees Payment", null, $student->user_id, 2);
            sendNotification("Add Fees Payment", null, $student->parents->user_id, 3);
            sendNotification("Add Fees Payment", null, 1, 1);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        // Toastr::success('Save Successful', 'Success');

        $response = [
            'success' => true,
            'data'    => null,
            'message' => 'Fees payment save successfully',
        ];
        return response()->json($response);
    }

    public function feesInvoiceView(Request $request)
    {
        // $data['generalSetting'] = SmGeneralSettings::select('logo', 'school_name', 'phone', 'email', 'address')->where('school_id', auth()->user()->school_id)->first();

        $invoiceInfo = FmFeesInvoice::withoutGlobalScope(AcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->fees_invoice_id)
            ->first();

        $data['invoiceInfo'] = new FmFeesInvoiceViewResource($invoiceInfo);

        $invoiceDetails = FmFeesInvoiceChield::where('fees_invoice_id', $data['invoiceInfo']->id)
            ->where('school_id', auth()->user()->school_id)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->get();

        $data['invoiceDetails'] = FmFeesInvoiceChieldViewResource::collection($invoiceDetails);

        $data['banks'] = SmBankAccount::withoutGlobalScope(ActiveStatusSchoolScope::class)
            ->where('active_status', 1)
            ->where('school_id', auth()->user()->school_id)
            ->select('id', 'bank_name', 'account_name', 'account_number', 'account_type')
            ->get();
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
                'message' => 'Fees invoice detail'
            ];
        }
        return response()->json($response);
    }

    public function studentFees(Request $request)
    {
        $fees_assign = DirectFeesInstallmentAssign::where('school_id', auth()->user()->school_id)
            ->where('record_id', $request->record_id)
            ->get();

        $data['fees_installment_list'] = FeesInstallmentListResource::collection($fees_assign);

        $total_fees = 0;
        $total_paid = 0;
        $total_disc = 0;
        $balance_fees = 0;
        foreach ($fees_assign as $key => $feesInstallment) {
            $total_fees += discount_fees($feesInstallment->amount, $feesInstallment->discount_amount);
            $total_paid += $feesInstallment->paid_amount;
            $total_disc += $feesInstallment->discount_amount;
            $balance_fees += discount_fees($feesInstallment->amount, $feesInstallment->discount_amount) - ($feesInstallment->paid_amount);
        }
        $result =  [
            'grand_total'       => (string)currency_format($total_fees),
            'total_discount'    => (string)currency_format($total_disc),
            'total_paid'        => (string)currency_format($total_paid),
            'total_balance'     => (float)$total_fees - ($total_paid),
        ];
        $data['payment_summary'] = $result;

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
                'message' => 'Fees installment list'
            ];
        }
        return response()->json($response);
    }

    public function studentSingleFees(Request $request)
    {
        $request->validate([
            'fees_installment_id' => ['required', Rule::exists('direct_fees_installment_assigns', 'id')->where('school_id', auth()->user()->school_id)]
        ]);

        $fees_assign = DirectFeesInstallmentAssign::where('school_id', auth()->user()->school_id)->findOrFail($request->fees_installment_id);
        $data['fees_installment_list'] = new FeesInstallmentListResource($fees_assign);
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
                'message' => 'Fees installment detail'
            ];
        }
        return response()->json($response);
    }

    public function directFeesGenerateModalChild(Request $request)
    {
        $data['installment_id'] = $request->installment_id;
        $record_id = $request->record_id;

        $std_info = StudentRecord::where('school_id', auth()->user()->school_id)->find($record_id);
        $data['student_id'] = $std_info->student_id;
        $data['record_id'] = $record_id;
        $discounts = SmFeesAssignDiscount::where('student_id', $std_info->student_id)->where('record_id', $record_id)->where('school_id', auth()->user()->school_id)->get();

        $data['banks'] = SmBankAccount::select('id', 'bank_name', 'account_name')->where('active_status', '=', 1)
            ->where('school_id', auth()->user()->school_id)
            ->get();

        $applied_discount = [];
        foreach ($discounts as $fees_discount) {
            $fees_payment = SmFeesPayment::where('record_id', $record_id)->where('active_status', 1)->select('fees_discount_id')->where('fees_discount_id', $fees_discount->id)->where('school_id', auth()->user()->school_id)->first();
            if (isset($fees_payment->fees_discount_id)) {
                $applied_discount[] = $fees_payment->fees_discount_id;
            }
        }

        $data1['bank_info'] = SmPaymentGatewaySetting::where('gateway_name', 'Bank')->where('school_id', auth()->user()->school_id)->first();
        $data1['cheque_info'] = SmPaymentGatewaySetting::where('gateway_name', 'Cheque')->where('school_id', auth()->user()->school_id)->first();
        $data['bank_info'] = SmPaymentMethhod::select('id', 'method', 'type')->where('method', 'Bank')->where('school_id', auth()->user()->school_id)->first();
        $data1['cheque_info'] = SmPaymentGatewaySetting::where('gateway_name', 'Cheque')->where('school_id', auth()->user()->school_id)->first();
        $data['cheque_info'] = SmPaymentMethhod::select('id', 'method', 'type')->where('method', 'Cheque')->where('school_id', auth()->user()->school_id)->first();
        $data1['PayPal'] = SmPaymentMethhod::where('method', 'PayPal')
            ->where('school_id', auth()->user()->school_id)
            ->first('active_status');
        $data1['Stripe'] = SmPaymentMethhod::where('method', 'Stripe')
            ->where('school_id', auth()->user()->school_id)
            ->first('active_status');
        $data1['Paystack'] = SmPaymentMethhod::where('method', 'Paystack')
            ->where('school_id', auth()->user()->school_id)
            ->first('active_status');
        if (moduleStatusCheck('CcAveune')) {
            $data1['CcAveune'] = SmPaymentMethhod::where('method', 'CcAveune')
                ->where('school_id', auth()->user()->school_id)
                ->first('active_status');
        }

        if (moduleStatusCheck('PhonePay')) {
            $data1['PhonePe'] = SmPaymentMethhod::where('method', 'PhonePay')
                ->where('school_id', auth()->user()->school_id)
                ->first('active_status');
        }

        $data['PayPal'] = SmPaymentGatewaySetting::where('gateway_name', 'PayPal')->where('school_id', auth()->user()->school_id)->first();
        $data['Stripe'] = SmPaymentGatewaySetting::where('gateway_name', 'Stripe')->where('school_id', auth()->user()->school_id)->first();
        $data['Paystack'] = SmPaymentGatewaySetting::where('gateway_name', 'Paystack')->where('school_id', auth()->user()->school_id)->first();
        $data['PhonePe'] = SmPaymentGatewaySetting::where('gateway_name', 'PhonePay')->where('school_id', auth()->user()->school_id)->first();
        $installment = DirectFeesInstallmentAssign::find($request->installment_id);
        $data['balance_fees'] = discountFees($request->installment_id)  - $installment->payments->sum('paid_amount');

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
                'message' => 'Operation successful'
            ];
        }
        return response()->json($response);
    }


    public function childBankSlipStore(Request $request)
    {
        if ($request->payment_mode == 'PayPal' || $request->payment_mode == 'Stripe' || $request->payment_mode == 'Paystack' || $request->payment_mode == 'CcAveune' || $request->payment_mode == 'PhonePay') {
            if (directFees()) {
                $request->validate([
                    'installment_id' => "required",
                    'amount' => "required|regex:/^\d+(\.\d{1,2})?$/",
                    'payment_mode' => "required"
                ]);

                $date = strtotime($request->date);
                $newformat = date('Y-m-d', $date);

                $installment = DirectFeesInstallmentAssign::find($request->installment_id);
                $installment->payment_date =  $newformat;
                $installment->payment_mode = $request->payment_mode;
                $installment->active_status = 0;
                // $installment->save();
                $payable_amount =  discountFees($installment->id);
                $sub_payment = $installment->payments->sum('paid_amount');

                $last_inovoice = DireFeesInstallmentChildPayment::where('school_id', auth()->user()->school_id)->max('invoice_no');
                $new_subPayment = new DireFeesInstallmentChildPayment();
                $new_subPayment->direct_fees_installment_assign_id = $installment->id;
                $new_subPayment->invoice_no = ($last_inovoice + 1) ?? 1;
                $new_subPayment->amount = $request->amount;
                $new_subPayment->paid_amount = $request->amount;
                $new_subPayment->payment_date = $newformat;
                $new_subPayment->payment_mode =  $request->payment_mode;
                $new_subPayment->note = $request->note;
                $new_subPayment->active_status = 0;
                $new_subPayment->discount_amount = 0;
                $new_subPayment->fees_type_id =  $installment->fees_type_id;
                $new_subPayment->student_id = $request->student_id;
                $new_subPayment->record_id = $request->record_id;
                $new_subPayment->created_by = auth()->user()->id;
                $new_subPayment->updated_by =  auth()->user()->id;
                $new_subPayment->school_id = auth()->user()->school_id;
                $new_subPayment->balance_amount = ($payable_amount - ($sub_payment + $request->amount));
                $new_subPayment->save();
                $data = [];
                $serviceCharge = 0;
                $gateway_setting = SmPaymentGatewaySetting::where('gateway_name', $request->payment_mode)->where('school_id', auth()->user()->school_id)->first();
                if ($gateway_setting) {
                    $serviceCharge = chargeAmount($request->payment_mode, $request->amount);
                }

                $data['amount'] = $request->amount;
                $data['service_charge'] = chargeAmount($request->payment_mode, $request->amount);
                $data['stripeToken'] = $request->stripeToken;
                $data['sub_payment_id'] = $new_subPayment->id;
                $data['installment_id'] = $installment->id;
                $data['record_id'] = $installment->id;
                $data['student_id'] = $installment->student_id;
                $data['student_id'] = $installment->student_id;
                $data['type'] = "direct_fees";
                $data['user_id'] = @$new_subPayment->student->id;
                $data['method'] = $request->payment_mode;
                $data['description'] = generalSetting()->school_name . " Fees Installment";
                // if ($request->payment_mode == 'CcAveune') {
                //     $data['merchant_id'] = @$gateway_setting->cca_merchant_id;
                //     $data['order_id'] = $data['type'] . '_' . $new_subPayment->id;
                //     $data['currency'] = generalSetting()->currency;
                //     $data['redirect_url'] = route('payment.success', 'CcAveune');
                //     $data['cancel_url'] = route('payment.cancel', 'CcAveune');
                //     $merchant_data = '';
                //     $working_key = @$gateway_setting->cca_working_key; //Shared by CCAVENUES
                //     $access_code = @$gateway_setting->cca_access_code; //Shared by CCAVENUES

                //     foreach ($data as $key => $value) {
                //         $merchant_data .= $key . '=' . urlencode($value) . '&';
                //     }
                //     $encrypted_data = $this->encrypt($merchant_data, $working_key);
                //     return view('ccaveune::redirecformPage', compact('encrypted_data', 'access_code'));
                // }
                $classMap = config('paymentGateway.' . $data['method']);
                $make_payment = new $classMap();
                return $make_payment->handle($data);
            }
        } else {
            $request->validate([
                'slip' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
                'amount' => "required|regex:/^\d+(\.\d{1,2})?$/"
            ]);
        }


        $student_record = StudentRecord::find($request->record_id);

        if ($request->payment_mode == "bank") {
            if ($request->bank_id == '') {
                $response = [
                    'status'  => false,
                    'data' => 'Bank Field Required',
                    'message' => 'Bank Field Required',
                ];
                return response()->json($response, 401);
            }
        }

        $fileName = "";
        if ($request->file('slip') != "") {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('slip');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if ($fileSizeKb >= $maxFileSize) {
                $response = [
                    'status'  => false,
                    'data' => 'Max upload file size ' . $maxFileSize . ' Mb is set in system',
                    'message' => 'Max upload file size ' . $maxFileSize . ' Mb is set in system',
                ];
                return response()->json($response, 401);
            }
            $file = $request->file('slip');
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/bankSlip/', $fileName);
            $fileName = 'public/uploads/bankSlip/' . $fileName;
        }

        $date = strtotime($request->date);

        $newformat = date('Y-m-d', $date);

        $payment_mode_name = ucwords($request->payment_mode);
        $payment_method = SmPaymentMethhod::where('method', $payment_mode_name)->first();

        $payment = new SmBankPaymentSlip();
        $payment->date = $newformat;
        $payment->amount = $request->amount;
        $payment->note = $request->note;
        $payment->slip = $fileName;
        $payment->student_id = $request->student_id;
        $payment->payment_mode = $request->payment_mode;
        if ($payment_method->id == 3) {
            $payment->bank_id = $request->bank_id;
        }
        $payment->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
        if (moduleStatusCheck('University')) {
            $payment->un_academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            $payment->un_fees_installment_id  = $request->installment_id;
            $payment->un_semester_label_id = $request->un_semester_label_id;

            $installment = UnFeesInstallmentAssign::find($request->installment_id);
            $installment->payment_date =  $newformat;
            $installment->payment_mode = $request->payment_mode;
            $installment->note = $request->note;
            $installment->slip = $fileName;
            $installment->active_status = 0;
            if ($payment_method->id == 3) {
                $installment->bank_id  = $request->bank_id;
            }
            $installment->save();

            $payable_amount =  discountFeesAmount($installment->id);
            $sub_payment = $installment->payments->sum('paid_amount');

            $last_inovoice = UnFeesInstallAssignChildPayment::where('school_id', auth()->user()->school_id)->max('invoice_no');
            $new_subPayment = new UnFeesInstallAssignChildPayment();
            $new_subPayment->un_fees_installment_assign_id = $installment->id;
            $new_subPayment->invoice_no = ($last_inovoice + 1) ?? 1;
            $new_subPayment->amount = $request->amount;
            $new_subPayment->paid_amount = $request->amount;
            $new_subPayment->payment_date = $newformat;
            $new_subPayment->payment_mode =  $request->payment_mode;
            $new_subPayment->note = $request->note;
            $new_subPayment->slip = $fileName;
            $new_subPayment->active_status = 0;
            $new_subPayment->bank_id = $request->bank_id;
            $new_subPayment->discount_amount = 0;
            $new_subPayment->fees_type_id =  $installment->fees_type_id;
            $new_subPayment->student_id = $request->student_id;
            $new_subPayment->record_id = $request->record_id;
            $new_subPayment->un_semester_label_id = $request->un_semester_label_id;;
            $new_subPayment->un_academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            $new_subPayment->created_by = auth()->user()->id;
            $new_subPayment->updated_by =  auth()->user()->id;
            $new_subPayment->school_id = auth()->user()->school_id;
            $new_subPayment->balance_amount = ($payable_amount - ($sub_payment + $request->amount));
            $new_subPayment->save();

            $payment->child_payment_id = $new_subPayment->id;
        } elseif (directFees()) {
            $payment->class_id = $student_record->class_id;
            $payment->section_id = $student_record->section_id;
            $payment->record_id = $student_record->id;
            $payment->school_id = auth()->user()->school_id;
            $installment = DirectFeesInstallmentAssign::find($request->installment_id);
            $installment->payment_date =  $newformat;
            $installment->payment_mode = $request->payment_mode;
            $installment->note = $request->note;
            $installment->slip = $fileName;
            $installment->active_status = 0;
            if ($payment_method->id == 3) {
                $installment->bank_id  = $request->bank_id;
            }
            $installment->save();
            $payable_amount =  discountFees($installment->id);
            $sub_payment = $installment->payments->sum('paid_amount');

            $last_inovoice = DireFeesInstallmentChildPayment::where('school_id', auth()->user()->school_id)->max('invoice_no');
            $new_subPayment = new DireFeesInstallmentChildPayment();
            $new_subPayment->direct_fees_installment_assign_id = $installment->id;
            $new_subPayment->invoice_no = ($last_inovoice + 1) ?? 1;
            $new_subPayment->amount = $request->amount;
            $new_subPayment->paid_amount = $request->amount;
            $new_subPayment->payment_date = $newformat;
            $new_subPayment->payment_mode =  $request->payment_mode;
            $new_subPayment->note = $request->note;
            $new_subPayment->slip = $fileName;
            $new_subPayment->active_status = 0;
            $new_subPayment->bank_id = $request->bank_id;
            $new_subPayment->discount_amount = 0;
            $new_subPayment->fees_type_id =  $installment->fees_type_id;
            $new_subPayment->student_id = $request->student_id;
            $new_subPayment->record_id = $request->record_id;
            $new_subPayment->created_by = auth()->user()->id;
            $new_subPayment->updated_by =  auth()->user()->id;
            $new_subPayment->school_id = auth()->user()->school_id;
            $new_subPayment->balance_amount = ($payable_amount - ($sub_payment + $request->amount));
            $new_subPayment->save();

            $payment->child_payment_id = $new_subPayment->id;
            $payment->installment_id = $request->installment_id;
        } else {
            $payment->assign_id = $request->assign_id;
            $payment->class_id = $request->class_id;
            $payment->section_id = $request->section_id;
            $payment->record_id = $request->record_id;
            $payment->school_id = auth()->user()->school_id;
            $payment->fees_type_id = $request->fees_type_id;
        }

        $payment->save();
        $response = [
            'success' => true,
            'data'    => null,
            'message' => 'Payment Added, Please Wait for approval',
        ];
        return response()->json($response, 200);
    }

    public function feesPaymentPrint(Request $request)
    {
        if (checkAdmin()) {
            $payment = SmFeesPayment::where('school_id', auth()->user()->school_id)->where('id', $request->fees_payment_id)->first();
        } else {
            $payment = SmFeesPayment::where('active_status', 1)
                ->where('id', $request->fees_payment_id)
                ->where('school_id', auth()->user()->school_id)->first();
        }

        $group = $request->group;
        $student = SmStudent::withoutGlobalScope(SchoolScope::class)->where('id', $payment->student_id)->first();
        $pdf = Pdf::loadView('backEnd.feesCollection.fees_payment_print', ['payment' => $payment, 'group' => $group, 'student' => $student]);
        return $pdf->stream(date('d-m-Y') . '-' . $student->full_name . '-fees-payment-details.pdf');
    }
}