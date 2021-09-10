<?php

namespace App\Http\Middleware;
use Firebase\JWT\JWT;
use Closure;
use DomainException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            if(Session::has('jwt_token')){
                $decoded = JWT::decode(Session::get('jwt_token'), getenv('JWT_SECRET'), array('HS256'));
                $request->user = $decoded;
                return $next($request);
            }
            return redirect('/');
        }catch(DomainException $e){
            return $request->redirect('/');
        }
    }
}
