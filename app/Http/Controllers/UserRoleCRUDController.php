<?php

namespace App\Http\Controllers;

use App\User;
use App\UserRole;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        $userrole = new UserRole;
         // Check record existence
        try
        {
            $userrole = UserRole::where('userid', '=', $request->userid)->where('roleid', '=', $request->roleid)->firstOrFail();
        }
        catch(ModelNotFoundException $e)
        {
            // Update the record
            $userrole->userid = $request->userid;
            $userrole->roleid = $request->roleid;
            $userrole->save();
        }

        // Return a success
        return response()->json(['id'=>$userrole->id, 'userid'=>$request->userid, 'roleid'=>$request->roleid], $this->successStatus);        
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
    public function destroy($roleId)
    {
        $result = UserRole::destroy($roleId);
        // Delete success 200 or fail 404 responses
        if($result){
            return response()->json(['id'=>$roleId], $this->successStatus);
        }
        else{
            return response()->json(['id'=>$roleId], $this->errorNotFound);
        }
    }
}
