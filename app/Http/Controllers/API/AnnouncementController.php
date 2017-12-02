<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{

    public $successStatus = 200;

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
        
        $announcements = DB::table('announcements')->where([
            ['source', '=', $search['source']],
            ['visible', '=', 'Y'],
        ])->latest()->get();
        
        // Nothing returned send an error (this might be better to just send an empty announcement)
        if ($announcements->count() == 0) {
           return response()->json(['error'=>['empty' => ['There are no new announcements for this category.']]], 401);
        }

        // Send the json annoucements (review paginations later)
        return response()->json(['success'=>$announcements], $this->successStatus);
    }

}