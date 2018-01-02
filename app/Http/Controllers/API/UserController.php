<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Validator;

class UserController extends Controller
{

    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(){
     // Check login details
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
         // Correct password entered check the user has confirmed the registration or password change
            if($this->emailConfirmationCompleted($user)){
                           return response()->json(['success' => $success], $this->successStatus);
            }
         // Confirmation link has not been clicked
            return response()->json(['error'=>'You must click the link in the email sent to you'], 401);
        }
        else{
         // Login details were incorrect
            return response()->json(['error'=>'Incorrect username or password'], 401);
        }
    }
    
    public function emailConfirmationCompleted($user){
      // Check if there is a validation email link to be clicked
         $valid = DB::table('uservalidate')->where('userid', $user->id)->count();
        
         if ($valid == 0){
             return true;
         }
         else {
             return false;
         }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $input = $request->all();
        // Changed for consistency with password controller
        $input['password'] = Hash::make($input['password']);
        //$input['password'] = bcrypt($input['password']);  
        // Note this needs a duplicate user check
        if (User::where('email', '=', $input['email'])->count() > 0) {
           return response()->json(['error'=>['email' => ['Your email exists on the system reset your password.']]], 401);
        }
        
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return response()->json(['success'=>$success], $this->successStatus);
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }
}