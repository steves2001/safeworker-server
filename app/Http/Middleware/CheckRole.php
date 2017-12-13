<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permitted)
    {
        $permittedRoles = explode('+', $permitted);

        if(Auth::check() && Auth::user()->id){
            // $id = $request->user()->id;
            $id = Auth::id();
            
            $userRoles = DB::table('userroles')
                   ->join('roles', 'userroles.roleid', '=', 'roles.id')
                   ->where('userroles.userid', '=', $id)
                   ->pluck('role');
            
            //return response()->json($userRoles, 401);
            
            if(!empty($userRoles))
            foreach($userRoles as $userRole){
                if(in_array($userRole, $permittedRoles))
                    return $next($request);
            }
            
        }
        
        return response()->json(['error'=>'Unauthorised'], 401);
    }
}
