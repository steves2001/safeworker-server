<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

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
    public function update(Request $request, User $user)
    {
        //
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
