<?php

namespace App\Http\Controllers;

use App\User;
use App\Mail\RegistrationConfirmation;
use App\Http\Resources\UserResource;

use Validator;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserCRUDController extends Controller
{
    // List of columns in table
    protected $columns = [
        ['field'=>'select', 'checkbox'=>true, 'align'=>'center', 'valign'=>'middle'], 
        ['field'=>'id', 'title'=>'Id', 'align'=>'right'], 
        ['field'=>'name', 'title'=>'Name'], 
        ['field'=>'email', 'title'=>'Email'], 
        ['field'=>'created_at', 'title'=>'Created'], 
        ['formatter'=>'userTableActions', 'title'=>'Action', 'align'=>'center'],
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], $this->errorStatus);            
        }
        
        $emailDetails = explode('@', $request->email, 3);
     // Email must be either 999999@student.lincolncollege.ac.uk or AAAAAAAA@lincolncollege.ac.uk
        if(count($emailDetails) >= 2)
            if( !( (strlen($emailDetails[0]) == 6 && is_numeric($emailDetails[0]) && $emailDetails[1] == 'student.lincolncollege.ac.uk') 
                || (ctype_alpha ($emailDetails[0]) && $emailDetails[1] == 'lincolncollege.ac.uk') ) )
            return response()->json(['error'=>['email'=>'Email is not a valid lincolncollege email address']], $this->errorStatus);

        $input = $request->all();
     // Changed for consistency with password controller
        $input['password'] = Hash::make($input['password']);
     // Duplicate user check
        if (User::where('email', '=', $input['email'])->count() > 0) {
           return response()->json(['error'=>['email' => ['User email exists on the system.']]], $this->errorStatus);
        }
        
        $user = User::create($input);
        //$success['token'] =  $user->createToken('MyApp')->accessToken;
        //$success['name'] =  $user->name;

        return $this->emailConfirmation($user);
                
    }
    
    protected function emailConfirmation($user)
    {
     // Generate confirmation token
        $validationToken = Str::random(60);
         
     // Clear existing reset entries for the user
        DB::table('uservalidate')->where('userid', $user->id)->delete();

     // Store in the uservalidate table
        DB::table('uservalidate')->insert(
                ['created_at' => date("Y-m-d H:i:s"),
                 'userid' => $user->id, 
                 'temppassword' => '', 
                 'validationtoken' => $validationToken]
         );
     // Send email with token and link to route to complete resetPassword
        Mail::to($user->email)->send(new RegistrationConfirmation($validationToken, $user->name));
          
     // Send success message
        return response()->json(['success'=>'Confirmation link sent to users email address to complete the registration.'], $this->successStatus);  
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
        //return response()->json(['id'=>$userId], $this->errorNotFound);
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
