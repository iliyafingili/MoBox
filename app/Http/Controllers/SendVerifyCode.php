<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PhoneRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Melipayamak\MelipayamakApi;
use Throwable;
use Hash;
class SendVerifyCode extends Controller
{
    public function CreateAndSendVerifyCode($request){

        $targetNumber = $request->input("phone");
        // $randomNumber = mt_rand(10000, max: 99999);
        $randomNumber = 12345;

        //برای بررسی در ادامه
        session([
            'VerifyCode' => Hash::make($randomNumber),
            //برای اعمال یک دقیقه تایم کد تایید
            'TimeCreditCode' => now(),
            'phone' => $targetNumber
        ]);

        if (RateLimiter::remaining($targetNumber, 5) <= 0) {
            return back()->withErrors([
            'phone' => 'تعداد درخواست بیش از حد مجاز است. لطفاً بعد از ۱۰ دقیقه دوباره تلاش کنید.'
            ]);
        }


        else {
            try {
                //شروع ارسال پیامک
                // $data = array('username' => "09944327803", 'password' => "27ed78fa-c421-4077-b155-84a076141067",
                // 'text' => "$randomNumber",'to' =>"$targetNumber" ,"bodyId"=>"364662");
                // $post_data = http_build_query($data);
                // $handle = curl_init('https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber');
                // curl_setopt($handle, CURLOPT_HTTPHEADER, array(
                //     'content-type' => 'application/x-www-form-urlencoded'
                // ));
                // curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
                // curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
                // curl_setopt($handle, CURLOPT_POST, true);
                // curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
                // $response = curl_exec($handle);
                //var_dump($response);
                //پایان ارسال پیامک

                RateLimiter::hit($targetNumber, 600);

                session(['CodeSended' => true,
                         'code_verified_expires' => now()->addMinutes(1)
                        ]);
                return redirect()->route("verify");
                } catch (Throwable $error) {
                // متن خطا (اگه JSON باشه)
                $message = $error->getMessage();

                // تلاش برای تبدیل به آرایه
                $data = json_decode($message, true);

                //ارور 11 ینی شماره مورد نظر در شبکه موجود نمیباشد
                if (json_last_error() === JSON_ERROR_NONE && isset($data['Value']) && $data['Value'] == 11) {
                    return back()->withErrors([
                        'phone' => 'شماره یافت نشد'
                    ]);
                } else {
                    return back()->withErrors([
                        'phone' => 'در حال حاضر این امکان وجود ندارد'
                 ]);
                }
        }
    }
}

    public function VerifyCode(Request $request){
        $VerifyCodeHashed = session()->get('VerifyCode');
        $VerifyCodeInputed = implode('', $request->input('verify_code'));
        $TimeCreditCode = session()->get('TimeCreditCode');

        if(!Hash::check($VerifyCodeInputed, $VerifyCodeHashed) || now()->diffInSeconds($TimeCreditCode) > 60){
           return back()->withErrors(['verify_code' => 'کد تایید صحیح نمیباشد']);
        }
        else{
            if(session('TypeForAfterVerify') == 'register'){
                if(session('ForRedirectrAfterVerify') == 'edit-phone'){
                    return app(EditProfileController::class)->UpdatePhone( $request);
                }
                else{
                session(['Verify' => true,
                         'code_verified_expires' => now()->addMinutes(1)
                        ]);
                return redirect()->route("set-username-password");
                }
            }
            elseif(session("TypeForAfterVerify") == "forgot"){
                return app(ForgotController::class)->CreateAndUpdatePassword( $request);
            }
            elseif(session("TypeForAfterVerify") == "edit-phone"){
                return redirect()->route('edit-profile')
                 ->withErrors(['NwePassword' => 'شماره با موفقیت تقیر کرد']);
            }
        };
    }
}
