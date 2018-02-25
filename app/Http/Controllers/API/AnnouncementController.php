<?php

namespace App\Http\Controllers\API;

use App\Announcement;
use App\Http\Resources\AnnouncementResource;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{
    // List of columns in table
    protected $columns = [
        ['field'=>'select', 'checkbox'=>true, 'align'=>'center', 'valign'=>'middle'], 
        ['field'=>'id', 'title'=>'Id', 'align'=>'right'], 
        ['field'=>'source', 'title'=>'Source'], 
        ['field'=>'title', 'title'=>'Title'], 
        ['field'=>'content', 'title'=>'Content'], 
        ['field'=>'visible', 'title'=>'Visible'], 
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
        // Return a list of all the announcements on the system.
        $data['columns'] = $this->columns;
        $data['data'] = AnnouncementResource::collection(Announcement::select('id','source','title','content','visible','created_at')->get());
        return response()->json($data);
    }
    

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
            'source' => 'numeric',
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
        
        if(array_key_exists('source', $search)){
            $announcements = DB::table('announcements')->where([
                ['source', '=', $search['source']],
                ['visible', '=', 'Y'],
            ])->latest()->paginate(4);            
        }
        else {
            return $this->index();                        
        }

        // Nothing returned send an error (this might be better to just send an empty announcement)
        if ($announcements->count() == 0) {
           return response()->json(['error' => 'There are no new announcements for this category.'], 204);
        }

        // Send the json annoucements (review paginations later)
        return response()->json(['success'=>$announcements], $this->successStatus);
    }

}