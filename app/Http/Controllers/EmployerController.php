<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use App\Models\Employer as ModelsEmployer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Session::has('jwt_token')) redirect('/');

        $master = JWT::decode(Session::get('jwt_token'), getenv('JWT_SECRET'), array('HS256'));
        $master_id = $master->id;        
        $employee = ModelsEmployer::where('master_id',$master_id)->get();
        $response = APIResponse(true, 200, '', '', $employee);
        return response($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            "name"=>"required",
            "email"=>"required|email",
            "password"=>"required",
        ]);

        if(!Session::has('jwt_token')) return redirect('/');
        if(
            ModelsEmployer::where('email',$request->email)->get()->count() > 0
            ||
            User::where('email',$request->email)->get()->count() > 0
        ){
            return Response(["message"=>"Email id is already taken"],401);
        }
        $master = JWT::decode(Session::get('jwt_token'), getenv('JWT_SECRET'), array('HS256'));
        $master_id = $master->id;        
        $employer = new ModelsEmployer();
        $employer->name = $request->name;
        $employer->email = $request->email;
        $employer->password = Hash::make($request->password);
        $employer->master_id = $master_id;
        $employer->role = 0;
        $employer->save();
        $response = APIResponse(true, 200, '', 'Employer Created Successfully!!', []);
        return response($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function downloadData(Request $request)
    {
        if(!Session::has('jwt_token')) redirect('/');

        $master = JWT::decode(Session::get('jwt_token'), getenv('JWT_SECRET'), array('HS256'));

        $master_id = $master->id;
        $query = "SELECT
        emp.id as 'id',
        emp.name as 'name',
        emp.email as 'email',
        emp.role as 'employer role',
        emp.master_id as 'master id',
        user.name as 'added by',
        user.email as 'added by email'
            FROM 
        employeeManagement.employer as emp 
            join 
        employeeManagement.users as user 
            where
        user.id = emp.master_id and user.id = $master_id";

        $employers = DB::select($query);
        
        return $employers;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {   
        if(!Session::has('jwt_token')) redirect('/');

        $master = JWT::decode(Session::get('jwt_token'), getenv('JWT_SECRET'), array('HS256'));

        $master_id = $master->id;

        $affectedRows = 0;

        $data = $request->data;

        for($i=0; $i<count($data); $i++){
            $email = $data[$i]['email'];
            if( !(ModelsEmployer::where('email',$email)->get()->count() > 0
                ||
                User::where('email',$email)->get()->count() > 0)){
                    $employer = new ModelsEmployer;
                    $employer->name = $data[$i]['name'];
                    $employer->email = $data[$i]['email'];
                    $employer->password = Hash::make($data[$i]['password']);
                    $employer->role = $data[$i]['role'];
                    $employer->master_id = $master_id;
                    $employer->save();
                    $affectedRows+=1;
                }
        }
        return $affectedRows;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'role'=>'required',
        ]);

        $user = ModelsEmployer::find($request->id);
        $user->role = $request->role;
        $user->save();
        $response = APIResponse(true, 200, '', '"Role updated successfully"', []);
        return response($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(!Session::has('jwt_token')) redirect('/');
        $request->validate([
            'id' => 'required',
        ]);
        $user = ModelsEmployer::find($request->id);
        $user->delete();
        $response = APIResponse(true, 200, '', 'Employer deleted successfully!', []);
        return response($response, 200);
    }
}
