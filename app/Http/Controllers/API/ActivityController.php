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
        /* Enable when basic functionality confirmed
        $rowid  = DB::table('activities')->insertGetId([
            'created_at' => date("Y-m-d H:i:s"), 
            'starttime'=> date("Y-m-d H:i:s"),
            'endtime' => date("Y-m-d H:i:s"),
            'location' => 'Unknown',
            'escortrequired' => 'Y', 
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
            'starttime'=> date("Y-m-d H:i:s"),
            'endtime' => date("Y-m-d H:i:s")
        ];
        
        
        // Check for a valid source id
        //$validator = Validator::make($request->all(), [
        //    'source' => 'required|numeric',
        //]);

        //if ($validator->fails()) {
        //    return response()->json(['error'=>$validator->errors()], 401);            
        //}

        // Extract all the announcements for this source (pagination needs to be investigated)
        //$search = $request->all();
        
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