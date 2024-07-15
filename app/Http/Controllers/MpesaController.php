<?php

namespace App\Http\Controllers;

class MpesaController extends Controller
{
    public function STKPushSimulation()
    {
        $mpesa = new \Safaricom\Mpesa\Mpesa();
        $BusinessShortCode = env('MPESA_SHORTCODE'); /* 6133198 /  till number 6174778*/
        $LipaNaMpesaPasskey = env('MPESA_PASSKEY');
        $TransactionType = "CustomerBuyGoodsOnline"; /* CustomerPayBillOnline/ CustomerBuyGoodsOnline */
        $Amount = "1";
        $PartyA = auth()->user()->phone_number;
        $PartyB = env('MPESA_TILL_NUMBER'); /* Till number 8069154 */
        $PhoneNumber = auth()->user()->phone_number; /* 254768930084 254723466711 254700606199*/
        $CallBackURL = env('MPESA_TEST_URL');
        $AccountReference = env('MPESA_TILL_NUMBER'); /* 8069154 narasimha-enterprises*/
        $TransactionDesc = "lipa Na M-PESA narasimha-enterprises";
        $Remarks = "Thank for paying!";

        $stkPushSimulation = $mpesa->STKPushSimulation(
            $BusinessShortCode,
            $LipaNaMpesaPasskey,
            $TransactionType,
            $Amount,
            $PartyA,
            $PartyB,
            $PhoneNumber,
            $CallBackURL,
            $AccountReference,
            $TransactionDesc,
            $Remarks
        );

        dd($stkPushSimulation);
    }

    public function stkPush(Request $request)
    {
        Log::info('STK Push endpoint hit');
        Log::info($request->all());
        return [
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
            'ThirdPartyTransID' => rand(3000, 10000),
        ];
    }

    public function confirmation(Request $request)
    {
        Log::info('Confirmation endpoint hit');
        Log::info($request->all());

        return [
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
            'ThirdPartyTransID' => rand(3000, 10000),
        ];
    }
}

/* private function checkTransactionStatus($transactionReference)
{
$status = $this->makeApiCallToCheckTransactionStatus($transactionReference);

if ($status === 'Success') {
return 'Success';
} elseif ($status === 'Pending') {
return 'Pending';
} else {
return 'Failed';
}
}

private function makeApiCallToCheckTransactionStatus($transactionReference)
{
$mpesa = new \Safaricom\Mpesa\Mpesa();

$BusinessShortCode = env('MPESA_SHORTCODE');
$LipaNaMpesaPasskey = env('MPESA_PASSKEY');
$CommandID = "TransactionStatusQuery";
$TransactionID = $transactionReference;
$PartyA = $BusinessShortCode;
$IdentifierType = "4";
$ResultURL = env('MPESA_TEST_URL');
$QueueTimeOutURL = env('MPESA_TEST_URL');

$transactionStatus = $mpesa->transactionStatus(
$BusinessShortCode,
$LipaNaMpesaPasskey,
$CommandID,
$TransactionID,
$PartyA,
$IdentifierType,
$ResultURL,
$QueueTimeOutURL
);

return $transactionStatus['TransactionStatus'];
} */

/* public function STKCallback(Request $request)
{
$CallBackURL = $request->all();

// Check if the response has an error
if ($CallBackURL['ResultCode'] == 0) {
// Insert Deposit
$deposit = $this->deposit(
auth()->user()->id,
'bt_' . str_random(25),
$this->request->amount,
'Mpesa',
$this->settings->tax_on_wallet ? auth()->user()->taxesPayable() : null,
);

// Notify Admin via Email
try {
Notification::route('mail', $this->settings->email_admin)
->notify(new AdminDepositPending($deposit));
} catch (\Exception$e) {
\Log::info($e->getMessage());
}

// Add Funds to User
User::find(auth()->user()->id)->increment('wallet', $this->request->amount);

return response()->json([
"success" => true,
"status" => 'pending',
'status_info' => __('general.pending_deposit'),
]);

return redirect('my/wallet');

} else {
return response()->json([
"success" => false,
"status" => 'pending',
'status_info' => __('general.pending_deposit'),
]);

}

} */

