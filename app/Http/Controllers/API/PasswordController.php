<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Mail\ResetPassword;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Validator;

class PasswordController extends Controller
{

    public $successStatus = 200;
    public $errorStatus = 400;

    /**
     * Reset the given user's password.
     *
     * @param  post request containing an email $email
     * @return string json success/error status
     */    
    public function resetPassword(Request $request)
    {
     // Validate the email submitted
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
     // If validation fails return json error
        if ($validator->fails()) {
            return response()->json(['error'=>'The email sent was not valid or missing.'], 401);            
        }

        $input = $request->all();        
     // Start the password reset process
        return $this->initiatePasswordReset($input['email']);
        
    }
        
    
    /**
     * Find the user and email the password reset message.
     *
     * @param  string  $email
     * @return json success/error status
     */
    protected function initiatePasswordReset($email)
    {
        // Get users id for the email       
          $user = DB::table('users')
                    ->where('email', $email)
                    ->first();
        
        // Check the email is for a valid user
        if($user){         
         // Generate token and random password
            $password = Str::random(8);
            $hashedPassword = Hash::make($password);
            $validationToken = Str::random(60);
         
         // Clear existing reset entries for the user
            DB::table('uservalidate')->where('userid', $user->id)->delete();

         // Store in the uservalidate table
            DB::table('uservalidate')->insert(
                ['created_at' => date("Y-m-d H:i:s"),
                 'userid' => $user->id, 
                 'temppassword' => $hashedPassword, 
                 'validationtoken' => $validationToken]
            );
         // Send email with token and link to route to complete resetPassword
            Mail::to($email)->send(new ResetPassword($password, $validationToken, $user->name));
          
         // Send success message
            return response()->json(['success'=>'A reset link has been sent to the email address provided.'], $this->successStatus);
        }
        
        // Send error message
        return response()->json(['error'=>'The email address provided does not match an email address on our system.'], $this->errorStatus);
       
    }
    
    /**
     * Respond to the confirmation link from the email sent by initiatePasswordReset().
     *
     * @param  string  validationtoken as part of the api
     * @return view success/error status
     */
    public function confirmReset(Request $request, $validationToken = '')
    {
        // If the token exists as part of the api call
        if($validationToken != '')
        {
         // Get the most recent reset request
            $resetDetails = DB::table('uservalidate')->where('validationtoken', $validationToken)->latest()->first();
            
         // If the query returned data copy the password into the user details
            if(!empty($resetDetails)){
              // Insert the new password into the users record
                 DB::table('users')->where('id', $resetDetails->userid)->update(['password' => $resetDetails->temppassword]);  
              // Clear the reset entry for the user
                 DB::table('uservalidate')->where('id', $resetDetails->id)->delete();
              // Display a success web page.
                 return view('resetsuccess');               
            }

        }
        
        return view('resetfail');
    }
    

}