<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Firebase\JWT\JWT;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public $token = true;

    public function createUser(Request $request){
        $request->validate([
            "name"=>"required",
            "email"=>"required",
            "password"=>"required",
        ]);

        if(User::where('email',$request->email)->get()->count() > 0){
            return Response(["message"=>"Email id is already taken"],401);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->user_type = 'admin';
        $user->save();
        $response = APIResponse(true, 200, '', 'Successfully User created!!', $user);
        return response($response, 200);
    }

    public function loginUser(Request $request){
        $request->validate([
            'email' =>'required',
            'password' =>'required',
        ]);

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $jwt = JWT::encode($user, env('JWT_SECRET_KEY'));
            \Session::put('api_token', 'Bearer '.$jwt);
            \Session::put('user_details', $user);
            return view('admin.authenticated',['user'=>$user,'jwt'=>$jwt]);
        }else{
            return view('admin.login',['error'=>"invalid credential"]);
        }
    }
    public function do_login(Request $request)
    {
        // dd($request->user_token);
        \Session::put('jwt_token',$request->user_token);
        \Session::put('user', $request->user);
        $jwt = \Session::get('jwt_token');
        $response = APIResponse(true, 200, '', 'Successfully Logged In', ['jwt'=>$jwt]);
        return response($response, 200)->withCookie('api_token',$jwt);
    }
    public function adminHome(Request $request){
        // dd($request->cookie('api_token'));
        $user = JWT::decode($request->token, getenv('JWT_SECRET_KEY'), array('HS256'));
        return view('admin.authenticated',['user'=>$user,'jwt'=>$request->token]);
    }

    public function addEmployee(Request $request){
        $request->validate([
            "name"=>"required",
            "email"=>"required|email",
            "password"=>"required",
        ]);
        if(!Session::has('jwt_token')) redirect('/');

        // return  Session::get('jwt_token');
        $master = JWT::decode(Session::get('jwt_token'), getenv('JWT_SECRET'), array('HS256'));

        if(User::where('email',$request->email)->get()->count() > 0){
            $response = APIResponse(true, 401, '', 'Email id is already taken!!', '');
            return response($response, 401);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->user_type = 'employee';
        $user->master_id = $master->id;
        $user->save();
        $response = APIResponse(true, 200, '', 'Successfully employer added!!', $user);
        return response($response, 200);
    }

    public function loginUserHome(Request $request){
        $user = JWT::decode($request->jwt, getenv('JWT_SECRET_KEY'), array('HS256'));
        return view('admin.authenticated',['user'=>$user,'jwt'=>$request->jwt]);
    }
    public function getEmployeesPage(Request $request){
        if(Session::has('jwt_token')){
            $user = JWT::decode(Session::get('jwt_token'), getenv('JWT_SECRET'), array('HS256'));
            return view('admin.updatePage',['user'=>$user,'jwt'=>Session::get('jwt_token')]);    
        }
        return redirect('/');
    }

    public function  getAllEmployer(Request $request){

        if(!Session::has('jwt_token')) redirect('/');

        $master = JWT::decode(Session::get('jwt_token'), getenv('JWT_SECRET'), array('HS256'));

        $employee = User::where('master_id',$master->id)->get();
        $response = APIResponse(true, 200, '', '', $employee);
        return response($response, 200);
    }

    public function updateRole(Request $request){
        
        $request->validate([
            'id' => 'required',
            'emp_role'=>'required',
        ]);

        $user = User::find($request->id);
        $user->emp_role = $request->emp_role;
        $user->save();
        $response = APIResponse(true, 200, '', '"Role updated successfully"', []);
        return response($response, 200);

    }

    public function deleteEmployer(Request $request){
        if(!Session::has('jwt_token')) redirect('/');
        $request->validate([
            'id' => 'required',
        ]);
        $user = User::find($request->id);
        $user->delete();
        $response = APIResponse(true, 200, '', 'Employer deleted successfully!', []);
        return response($response, 200);
    }

    public function register(Request $request)
    {
 
        $request->validate([
            "name"=>"required",
            "email"=>"required|email",
            "password"=>"required",
        ]);
 
        if(User::where('email',$request->email)->get()->count() > 0){
            return Response(["message"=>"Email id is already taken"],401);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();
  
        // if ($this->token) {
        //     return $this->login($request);
        // }
  
        $response = APIResponse(true, 200, '', 'Successfully User created!!', $user);
        return response($response, 200);
    }
     public function login(Request $request)
    {
        $request->validate([
            "email"=>"required",
            "password"=>"required",
        ]);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $jwt = JWT::encode($user, env('JWT_SECRET'));
            \Session::put('jwt_token', $jwt);
            \Session::put('user_details', $user);
            $response = APIResponse(true, 200, '', 'Login successfully!', ["user_token"=>$jwt,"user"=>$user]);
            return response($response, 200);
        }else{
            $response = APIResponse(true, 401, '', 'invalid credential', []);
            return response($response, 401);
        }
    }
  
    public function logout(Request $request)
    {
        Auth::logout();
        Session::forget('jwt_token');
        return redirect('/');
    }

}
