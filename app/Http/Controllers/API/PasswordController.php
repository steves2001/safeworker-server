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

    
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $input = $request->all();        
        
        return $this->initiatePasswordReset($input['email']);
        
    }
    
    public function confirmReset(Request $request, $confirmationToken = '')
    {
        if($confirmationToken != '')
        {
            return view('welcome');
        }
        
       abort(403, 'Unauthorized action.');
    }
    
    
    /**
     * Reset the given user's password.
     *
     * @param  string  $email
     * @return void
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
            $resetToken = Str::random(60);
         
         // Clear existing reset entries for the user
            DB::table('uservalidate')->where('userid', $user->id)->delete();

         // Store in the uservalidate table
            DB::table('uservalidate')->insert(
                ['created_at' => date("Y-m-d H:i:s"),
                 'userid' => $user->id, 
                 'temppassword' => $hashedPassword, 
                 'validationtoken' => $resetToken]
            );
         // Send email with token and link to route to complete resetPassword
            Mail::to($email)->send(new ResetPassword($password, $resetToken, $user->name));
          
         // Send success message
            return response()->json(['success'=>'A reset link has been sent to the email address provided.'], $this->successStatus);
        }
        
        // Send error message
        return response()->json(['error'=>'The email address provided does not match an email in our system.'], $this->errorStatus);
       
    }

}