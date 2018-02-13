<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{

    public $successStatus = 200;
    public $errorStatus = 400;
    

    /**
     * 
     *
     * @param  request object with the message and subject details
     * @return json success/error status
     */
    public function submitAnnouncement(Request $request)
    {
        $route = Route::current();
        $source = $route->getName();
       
     // validate the request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'announcement' => 'required|string',
        ]);
     // If validation fails return json error
        if ($validator->fails()) {
            return response()->json(['error'=>'The title or your message is missing.'], $this->errorStatus);            
        }
        
     // Check the source specified in the API call is a valid one
        $sourceId = DB::table('sources')->where('sourcename', $source)->value('id');
        if ($sourceId){
        // Insert the new announcement
            $input = $request->all();
            $rowid  = DB::table('announcements')->insertGetId([
                'created_at' => date("Y-m-d H:i:s"), 
                'source' => $sourceId, 
                'title' => $input['title'], 
                'content' => $input['announcement'],
                'visible' => 'Y'
            ]);
         // Return a success response
            return response()->json(['success'=>'Announcement has been posted. ' . $source ], $this->successStatus);   
        }
        
     // Source specified in the API call does not match a source in the sources table 
        return response()->json(['error'=>'Incorrect publisher specified.'], $this->errorStatus);
 
    }    
    
    /**
     * Announcement api
     *
     * @return \Illuminate\Http\Response
     */
    public function retrieveAnnouncements(Request $request)
    {
        // Check for a valid source id
        $validator = Validator::make($request->all(), [
            'source' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        // Extract all the announcements for this source (pagination needs to be investigated)
        $search = $request->all();
    
        // Factor in the pagination if supplied
        if(array_key_exists('page',$search)) {
            $currentPage = $search['page'];
            // Call the static method currentPageResolver() before querying
            Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
            });            
        } 
        
        $announcements = DB::table('announcements')->where([
            ['source', '=', $search['source']],
            ['visible', '=', 'Y'],
        ])->latest()->paginate(4);

        // Nothing returned send an error (this might be better to just send an empty announcement)
        if ($announcements->count() == 0) {
           return response()->json(['error' => 'There are no new announcements for this category.'], 204);
        }

        // Send the json annoucements (review paginations later)
        return response()->json(['success'=>$announcements], $this->successStatus);
    }

}