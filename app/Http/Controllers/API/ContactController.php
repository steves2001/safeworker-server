<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Mail\ContactDepartment; 
use App\Exceptions\Handler;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Validator;

class contactController extends Controller
{
    public $successStatus = 200;
    public $errorStatus = 400;
    public $serverError = 500;
    /**
     * 
     *
     * @param  request object with the message details
     * @return view success/error status
     */
    public function messageContact(Request $request)
    {
     // validate the request
        $validator = Validator::make($request->all(), [
            'contact' => [ 
                'required',
                Rule::in(['general', 'financial', 'careers', 'studentunion', 'safeguarding']),
            ],
            'enquiryMessage' => 'required|string',
        ]);
     // If validation fails return json error
       if ($validator->fails()) {
            return response()->json(['error'=>'The contact specified was not valid or your enquiry message is missing.'], $this->errorStatus);            
        }
 
        $input = $request->all();

        // Set contact email addresses

        
        $contactEmail = '';
        
        switch ($input['contact']) {
            case "safeguarding":
                $contactEmail = 'studentservices@lincolncollege.ac.uk';
                break;
            case "general":
                $contactEmail = 'studentservices@lincolncollege.ac.uk';
                break;
            case "financial":
                $contactEmail = 'welfare@lincolncollege.ac.uk';
                break;
            case "careers":
                $contactEmail = 'guidanceteam@lincolncollege.ac.uk';
                break;
            case "studentunion":
                $contactEmail = 'studentpresident@lincolncollege.ac.uk';
                break;
            default:
                $contactEmail = 'ssmith@lincolncollege.ac.uk';
        }

        // Send email to contact and user
        try {
            $user = Auth::user();
            Mail::to($contactEmail)->send(new ContactDepartment($user->email, $input['enquiryMessage'], $user->name, $contactEmail));
        } catch (Exception $e) {
            return response()->json(['error'=>'Server failed to send the email.'], $this->serverError); 
        }
        
        // Return a success response
        return response()->json(['success'=>'Message has been sent to ' . $contactEmail . '.' ], $this->successStatus);
    }

}