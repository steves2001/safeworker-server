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
        $rowid = 1;
        
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

        
         /* Enable when basic functionality confirmed 
        $rowid  = DB::table('activities')->insertGetId([
            'created_at' => date("Y-m-d H:i:s"), 
            'starttime'=> $sqlStartTime,
            'endtime' => $sqlEndTime,
            'location' => $input['location'],
            'escortrequired' => $input['escort'], 
            'phone' => '01427613288',
            'message' => 'Hello World', 
            'active' => 'Y', 
            'accepted' => 'N', 
            'userid' => $userid
        ]);
        */
        


        
        $results = [   
            'user'=> $userid,                     
            'activity'=> $rowid,                     
            'starttime'=> $responseStartTime,
            'endtime' =>  $responseEndTime
        ];

        //$announcements = DB::table('announcements')->where([
        //    ['source', '=', $search['source']],
        //    ['visible', '=', 'Y'],
        //])->latest()->get();
        
        // Nothing returned send an error (this might be better to just send an empty announcement)
        //if ($announcements->count() == 0) {
        //   return response()->json(['error'=>['empty' => ['There are no new announcements for this category.']]], 401);
        //}

        // Send the json annoucements (review paginations later)
        return response()->json(['success'=>$results], $this->successStatus);
    }
    
    // Updates the activity (Student)
    public function updateActivity(Request $request)
    {
        return response()->json($this->successStatus);
    }
    
    // Accepts the activity (Security only)
    public function acceptActivity(Request $request)
    {
        return response()->json($this->successStatus);
    }

}