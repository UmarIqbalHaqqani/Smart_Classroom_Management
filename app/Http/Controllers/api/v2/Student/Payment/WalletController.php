<?php

namespace App\Http\Controllers\api\v2\Student\Payment;

use App\User;
use App\SmBankAccount;
use App\SmAcademicYear;
use App\SmPaymentMethhod;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpParser\Node\Expr\Cast\Double;
use App\Scopes\ActiveStatusSchoolScope;
use App\Http\Resources\v2\WalletResource;
use Modules\Wallet\Entities\WalletTransaction;

class WalletController extends Controller
{
    public function myWallet()
    {
        $data['myBalance'] = auth()->user()->wallet_balance ? (float)number_format(auth()->user()->wallet_balance, 2, '.', '') : 0.00;
        $data['currencySymbol'] = (string)generalSetting()->currency_symbol;
        $walletTransactions = WalletTransaction::where('user_id', auth()->user()->id)
            ->where('school_id', auth()->user()->school_id)->orderBy('id', 'DESC')
            ->get();

        $data['walletTransactions'] = WalletResource::collection($walletTransactions);

        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => [$data],
                'message' => 'Wallet detail'
            ];
        }
        return response()->json($response);
    }

    public function addWalletAmount(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required',
            'payment_method' => 'required',
            'bank' => 'nullable|required_if:payment_method,2',
            'file' => 'nullable|mimes:jpg,jpeg,png,pdf'
        ]);

        $paymetnMethods = SmPaymentMethhod::withoutGlobalScope(ActiveStatusSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->find($request->payment_method);

        if ($request->payment_method == 2 || $request->payment_method == 3) {
            $uploadFile = null;
            if ($request->file('file')) {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('file');
                $fileSize = filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if ($fileSizeKb >= $maxFileSize) {
                    return response()->json(['error' => "Max upload file size $maxFileSize Mb is set in system",]);
                }
                $file = $request->file('file');
                $uploadFile = 'doc1-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/student/document/', $uploadFile);
                $uploadFile = 'public/uploads/student/document/' . $uploadFile;
            }

            $addPayment = new WalletTransaction();
            $addPayment->amount = $request->amount;
            $addPayment->payment_method = $paymetnMethods->method;
            $addPayment->bank_id = $request->bank;
            $addPayment->note = $request->note;
            $addPayment->file = $uploadFile;
            $addPayment->type = 'diposit';
            $addPayment->user_id = auth()->user()->id;
            $addPayment->school_id = auth()->user()->school_id;
            $addPayment->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            $data = $addPayment->save();

            try {
                // Notification Start
                $this->sendNotification(1, 1, "Wallet Request");

                $accounts_ids = User::where('role_id', 6)->get();
                foreach ($accounts_ids as $accounts_id) {
                    $this->sendNotification($accounts_id->id, $accounts_id->role_id, "Wallet Request");
                }
                // Notification End
            } catch (\Throwable $th) {
                //throw $th;
            }
        } else {
            $addPayment = new WalletTransaction();
            $addPayment->amount = $request->amount;
            $addPayment->payment_method = $paymetnMethods->method;
            $addPayment->user_id = auth()->user()->id;
            $addPayment->type = 'diposit';
            $addPayment->school_id = auth()->user()->school_id;
            $addPayment->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            $data = $addPayment->save();
        }

        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'Wallet amount submited successfully'
            ];
        }
        return response()->json($response);
    }

    public function paymentMethods()
    {
        if (auth()->user()->role_id == 2) {
            $methods = SmPaymentMethhod::withoutGlobalScope(ActiveStatusSchoolScope::class)
                ->where('school_id', auth()->user()->school_id)
                ->where('active_status', 1)
                ->whereNot('method', 'Wallet')
                ->whereNot('method', 'Cash')
                ->select('id', 'method')
                ->get();
        }

        if (!$methods) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $methods,
                'message' => 'Payment method list'
            ];
        }

        return response()->json($response);
    }

    public function bankAccounts()
    {
        $data = SmBankAccount::withoutGlobalScope(ActiveStatusSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->select('id', 'bank_name')
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
                'message' => 'Bank list'
            ];
        }
        return response()->json($response);
    }
}
