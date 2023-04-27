<?php

namespace App\Http\Controllers;

use App\Models\OTPModel;
use Illuminate\Http\Request;

class OTPController extends Controller
{
    function OtpListPage()
    {
        return view('OTP.OtpHistory');
    }

    function OtpListData()
    {
        $result = OTPModel::orderBy('id', 'desc')->get();
        return $result;
    }


    function loginByMobile(Request $request)
    {
        $created_timestamp = time();
        $created_time = date('h:i:sa');
        $created_date = date('d-m-y');
        $mobileNo = $request->input('mobile');
        $result = OTPModel::insert([
            'mobile' => $mobileNo,
            'created_timestamp' => $created_timestamp,
            'created_time' => $created_time,
            'created_date' => $created_date,
        ]);
        return $result;
    }
}
