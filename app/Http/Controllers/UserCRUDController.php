<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserCRUDController extends Controller
{
    // List of columns in table
    protected $columns = [
        ['field'=>'select', 'checkbox'=>true, 'align'=>'center', 'valign'=>'middle'], 
        ['field'=>'id', 'title'=>'Id', 'align'=>'right'], 
        ['field'=>'name', 'title'=>'Name'], 
        ['field'=>'email', 'title'=>'Email'], 
        ['field'=>'created_at', 'title'=>'Created'], 
        ['formatter'=>'userTableActions', 'title'=>'Action', 'align'=>'right'],
        ['field'=>'status', 'title'=>'Status', 'visible'=>'false']
    ];
    
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
        // Return a list of all the users on the system.
        $data['columns'] = $this->columns;
        $data['data'] = UserResource::collection(User::select('id','name','email','created_at')->get());
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update($userId, Request $request)
    {
        // Check record existence
        try
        {
            $user = User::findOrFail($userId);
        }
        catch(ModelNotFoundException $e)
        {
             return response()->json(['id'=>$userId], $this->errorNotFound);
        }
        
        // Check valid data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);            
        }
        
        // Update the record
        try
        {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
        }
        catch(\Exception $e)
        {
             return response()->json(['id'=>$userId], $this->errorConflict);
        }
        
        // Return a success
        return response()->json(['id'=>$userId], $this->successStatus);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId)
    {
        // You are forbidden 403 from deleing yourself
        $user = Auth::user();
        if($user->id == $userId) return response()->json(['id'=>$userId], $this->errorForbidden); 
        return response()->json(['id'=>$userId], $this->errorNotFound);
        //return response()->json(['id'=>$userId], $this->successStatus);
        // Delete the user
        $result = User::where('id',$userId)->delete();
        
        // Delete success 200 or fail 404 responses
        if($result){
            return response()->json(['id'=>$userId], $this->successStatus);
        }
        else{
            return response()->json(['id'=>$userId], $this->errorNotFound);            
        }
            
    }
}
