<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{

    public $successStatus = 200;

    /**
     * Activity api
     *
     * @return \Illuminate\Http\Response
     */
    
    // Logs the lone working activity (Student)
    public function logActivity(Request $request)
    {
        $userid = Auth::id();
        $rowid = 0;
        
        // Check for a valid data from the app
        $validator = Validator::make($request->all(), [
            'location' => 'required|string',
            'duration' => 'required|numeric',
            'escort' => 'required|in:Y,N'
        ]);

        if ($validator->fails()) {
            return response()->json(['req'=>$request->all(), 'error'=>$validator->errors()], 401);            
        }  
        
        $input = $request->all();
        
        //  Calc the logged start and end times for DB and Client
        $startTime = time();
        
        $sqlStartTime = date("Y-m-d H:i:s", $startTime);
        $responseStartTime = date("D, d M Y H:i:s", $startTime);

        $sqlEndTime   = date("Y-m-d H:i:s", $startTime + intval($input['duration'] * 60));
        $responseEndTime   = date("D, d M Y H:i:s", $startTime + intval($input['duration'] * 60));
        
        if(strlen($input['phone']) == 0) {
            $input['phone'] = 'No Phone';
        }

        if(strlen($input['message']) == 0) {
            $input['message'] = 'No Message';
        }
        // Clear any existing activities
        DB::table('activities')
            ->where('userid', $userid)
            ->update(['active' => 'N']);
        
        // Insert the new activity
        $rowid  = DB::table('activities')->insertGetId([
            'created_at' => date("Y-m-d H:i:s"), 
            'starttime'=> $sqlStartTime,
            'endtime' => $sqlEndTime,
            'location' => $input['location'],
            'escortrequired' => $input['escort'], 
            'phone' => $input['phone'],
            'message' => $input['message'], 
            'active' => 'Y', 
            'accepted' => 'N', 
            'userid' => $userid
        ]);
              
        // Record the inserted start and end times       
        $results = [   
            'user'=> $userid,                     
            'activity'=> $rowid,                     
            'starttime'=> $responseStartTime,
            'endtime' =>  $responseEndTime
        ];

        // Send the json annoucements (review paginations later)
        return response()->json(['success'=>$results], $this->successStatus);
    }
    
    // Updates the activity (Student)
    public function updateActivity(Request $request)
    {
        return response()->json($this->successStatus);
    }
    
    // Cancel the activity (Student/Security)
    public function cancelActivity(Request $request)
    {
        $userid = Auth::id();
        
        DB::table('activities')
            ->where('userid', $userid)
            ->update(['active' => 'N']);
        
        return response()->json($this->successStatus);
    }
    
    // Accepts the activity (Security only)
    public function acceptActivity(Request $request)
    {
        return response()->json($this->successStatus);
    }
    
    // Accepts the activity (Security only)
    public function activityStatus(Request $request)
    {
        $userid = Auth::id();
        $result = DB::table('activities')
            ->select('accepted')
            ->where('userid', $userid)
            ->where('active', 'Y')
            ->first();
        
        return response()->json(['success'=>$result], $this->successStatus);
    }

}