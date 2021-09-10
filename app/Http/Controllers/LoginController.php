<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;

class LoginController extends Controller
{
    public function do_login(Request $request)
    {
        \Session::put('api_token', 'Bearer '.$request->user_token);
        \Session::put('user_details', $request->user);
        $response = APIResponse(true, 200, '', 'Successfully Logged In', []);
        return response($response, 200);
    }
    public function admin_logout(Request $request)
    {
        $request->session()->flush();
        if($request->ajax())
        {
            Auth::guard('api')->logout();
            $response = APIResponse(true, 200, [], 'successfully logout.', []);
            return response($response, 200);
        }
        return redirect()->route('login');
    }
    public function session_check(Request $request)
    {
        print_r($request->session()->get('api_token'));
        return;
    }
}
