<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Exceptions\Handler;
use App\Mail\RegistrationConfirmation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Validator;

class UserController extends Controller
{

    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){
     // Check login details
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            
         // If the login is a staff login check it is a staff email  
            if ($request->is('api/login/staff')) {
                
                $emailDetails = explode('@', $request->email, 3);
             // Email must be AAAAAAAA@lincolncollege.ac.uk
                if(count($emailDetails) >= 2)
                    if(!(ctype_alpha ($emailDetails[0]) && $emailDetails[1] == 'lincolncollege.ac.uk'))  
                        return response()->json(['error'=>'Email is not a valid staff lincolncollege email address'], 401);
            }
        
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
        
        $emailDetails = explode('@', $request->email, 3);
     // Email must be either 999999@student.lincolncollege.ac.uk or AAAAAAAA@lincolncollege.ac.uk
        if(count($emailDetails) >= 2)
            if( !( (strlen($emailDetails[0]) == 6 && is_numeric($emailDetails[0]) && $emailDetails[1] == 'student.lincolncollege.ac.uk') 
                || (ctype_alpha ($emailDetails[0]) && $emailDetails[1] == 'lincolncollege.ac.uk') ) )
            return response()->json(['error'=>['email'=>'Email is not a valid lincolncollege email address']], 401);

        $input = $request->all();
     // Changed for consistency with password controller
        $input['password'] = Hash::make($input['password']);
     // Note this needs a duplicate user check
        if (User::where('email', '=', $input['email'])->count() > 0) {
           return response()->json(['error'=>['email' => ['Your email exists on the system reset your password.']]], 401);
        }
        
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        //return response()->json(['success'=>$success], $this->successStatus);
        // Enable when you complete the registration code.
        return $this->emailConfirmation($user);
        
    }    
    
    
    /**
     * Find the user and email the password reset message.
     *
     * @param  string  $email
     * @return json success/error status
     */
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
        return response()->json(['success'=>'Please click the confirmation link sent to your email address to complete the registration.'], $this->successStatus);  
    }    
    
    /**
     * Respond to the confirmation link from the email sent by emailConfirmation().
     *
     * @param  string  validationtoken as part of the api
     * @return view success/error status
     */
    public function confirmRegistration(Request $request, $validationToken = '')
    {
     // If the token exists as part of the api call
        if($validationToken != '')
        {
         // Get the most recent registration request
            $resetDetails = DB::table('uservalidate')->where('validationtoken', $validationToken)->latest()->first();
            
         // If the query returned data copy the password into the user details
            if(!empty($resetDetails)){
              // Clear the registration entry for the user
                 DB::table('uservalidate')->where('id', $resetDetails->id)->delete();
              // Display a success web page.
                 return view('registrationsuccess');               
            }
        }
        return view('registrationfail');
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