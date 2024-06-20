<?php

namespace App\Http\Controllers;

use App\Repositories\MpesaStkPush;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    // Initiate STK Push
    public function stkPushRequest(Request $request)
    {
        $accountReference = 'Transaction#' . Str::random(10);
        $amount = $request->amount;
        $phone = $this->formatPhone($request->phone_number);

        if (!$phone) {
            Session::flash('mpesa-error', 'Invalid phone number!');
            Session::flash('alert-class', 'alert-danger');

            return back();
        }

        $mpesa = new MpesaStkPush();
        $stk = $mpesa->lipaNaMpesa($amount, $phone, $accountReference);
        $response = json_decode($stk);

        if (isset($response->errorCode)) {
            Session::flash('mpesa-error', 'M-Pesa Error: ' . $response->errorMessage);
            Session::flash('alert-class', 'alert-danger');

            return back();
        }

        return redirect('/confirm/' . encrypt($accountReference));
    }

    private function formatPhone($phone)
    {
        // Remove leading '+' if present
        $phone = ltrim($phone, '+');

        // If phone number starts with '07' and has 10 digits, remove the leading '0' and add '254'
        if (preg_match('/^07[0-9]{8}$/', $phone)) {
            $phone = '254' . substr($phone, 1);
        }
        // If phone number starts with '011' and has 10 digits, remove the leading '0' and add '254'
        elseif (preg_match('/^01[0-9]{8}$/', $phone)) {
            $phone = '254' . substr($phone, 1);
        }
        // If phone number already starts with '254' and has 12 digits, do nothing
        elseif (preg_match('/^254[0-9]{9}$/', $phone)) {
            // Do nothing
        } else {
            // If the number does not match the expected patterns, return null
            return null;
        }

        return $phone;
    }

    public function checkTransactionStatus($transactionCode)
    {
        $mpesa = new MpesaStkPush();
        $status = $mpesa->status($transactionCode);

        return $status->ResponseCode ?? null;
    }
}