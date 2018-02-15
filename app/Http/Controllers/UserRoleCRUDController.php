<?php

namespace App\Http\Controllers;

use App\User;
use App\UserRole;
use Illuminate\Http\Request;

class UserRoleCRUDController extends Controller
{
    
    // HTTP Status codes
    public $successStatus = 200;
    public $errorStatus = 400;
    public $errorForbidden = 403;
    public $errorNotFound = 404;
    public $errorConflict = 409;
    public $errorUnauthorised = 401;
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return response()->json(UserRole::get());
    }
   
    public function indexByUser($userId)
    {
        return response()->json(UserRole::where('userid','=', $userId)->get());
    }

     
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {
        // Check valid data
        $validator = Validator::make($request->all(), [
            'userid' => 'required|integer',
            'roleid' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);            
        }
        
        // Update the record
        try
        {
            $userrole = new UserRole; 
            $userrole->userid = $request->userid;
            $userrole->roleid = $request->roleid;
            $userrole->save();
        }
        catch(\Exception $e)
        {
             return response()->json(['id'=>$request->userid, 'role'=>$request->roleid], $this->errorConflict);
        }
        
        // Return a success
        return response()->json(['id'=>$request->userid, 'role'=>$request->roleid], $this->successStatus);        
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */

    public function show(UserRole $userRole)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserRole $userRole)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserRole $userRole)
    {
        //
    }
}
