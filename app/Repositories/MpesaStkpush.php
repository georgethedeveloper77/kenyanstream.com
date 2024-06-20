<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class MpesaStkPush
{
    protected $consumerKey;
    protected $consumerSecret;
    protected $shortCode;
    protected $passKey;
    protected $callbackUrl;

    public function __construct()
    {
        $this->consumerKey = env('MPESA_CONSUMER_KEY');
        $this->consumerSecret = env('MPESA_CONSUMER_SECRET');
        $this->shortCode = env('MPESA_SHORTCODE');
        $this->passKey = env('MPESA_PASSKEY');
        $this->callbackUrl = env('MPESA_CALLBACK_URL');
    }

    public function lipaNaMpesa($amount, $phoneNumber, $accountReference)
    {
        $timestamp = date('YmdHis');
        $password = base64_encode($this->shortCode . $this->passKey . $timestamp);

        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)->post('https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest', [
            'BusinessShortCode' => $this->shortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phoneNumber,
            'PartyB' => $this->shortCode,
            'PhoneNumber' => $phoneNumber,
            'CallBackURL' => $this->callbackUrl,
            'AccountReference' => $accountReference,
            'TransactionDesc' => 'Payment for ' . $accountReference
        ]);

        \Log::info('STK Push Response: ', $response->json());

        return $response->body();
    }

    public function status($transactionCode)
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)->post('https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query', [
            'Initiator' => env('MPESA_INITIATOR'),
            'SecurityCredential' => env('MPESA_SECURITY_CREDENTIAL'),
            'CommandID' => 'TransactionStatusQuery',
            'TransactionID' => $transactionCode,
            'PartyA' => $this->shortCode,
            'IdentifierType' => '4',
            'ResultURL' => $this->callbackUrl,
            'QueueTimeOutURL' => $this->callbackUrl,
            'Remarks' => 'Transaction Status Query',
            'Occasion' => 'Transaction Status'
        ]);

        \Log::info('Transaction Status Response: ', $response->json());

        return json_decode($response->body());
    }

    protected function getAccessToken()
    {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get('https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');

        \Log::info('Access Token Response: ', $response->json());

        $data = json_decode($response->body());

        return $data->access_token;
    }
}