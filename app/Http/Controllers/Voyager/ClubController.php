<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Club;
use App\Models\User;
use App\Models\ClubUser;

use TCG\Voyager\Http\Controllers\VoyagerBaseController; 
use TCG\Voyager\Facades\Voyager;

use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClubController extends Controller
{  
       
    /**
     * Get post by id and toggle the status 
     * from Assigned to PENDING and vice versa
     */
    public function index() {
        
        $clubs = Club::clubCreatedBy()->orderBy('id', 'asc')->paginate(10);

        $otherUsersClub = Club::whereNot('created_by', Auth()->user()->id)
                                ->orderBy('id', 'asc')
                                ->paginate(10);

        $isAuth = Auth::user()->hasRole('admin');

        return view('/vendor/voyager/clubs/subscribe', compact('clubs', 'otherUsersClub', 'isAuth'));
    }

    /**
     * getSubscribeModel
     * 
     * this function return the clubs and users name on the model
     * 
     * @param {array} $request
     * @return {boolean}
     */
    public function getSubscribeModel(Request $request) {

        $data = $request->all();

        /*
            Don't Include the Admin;
        */
        $users = User::whereNot('id', 1)
                        ->orderBy('id', 'asc')
                        ->get();

        $clubs = Club::whereIn('id', $data['ids'])->get();   

        $html = view('/vendor/voyager/clubs/subscribe-model', [
                        'users' => $users, 
                        'clubs' => $clubs]
                    )->render();

        return response()->json(array('html'=> $html), 200); 
    }

    public function saveAssignData(Request $request) {

        $data = $request->all();

        $msg = "";

        if(!empty($data['source']) && $data['source'] == 'multiple') {

            $userIDs = $request['userIds'];

            $clubIDs = $request['clubIds'];

            $users = User::find($userIDs);

            $clubs = Club::find($clubIDs);
            
            
            foreach ($users as $user) {
                foreach ($clubs as $club) {
                    $msg = $this->saveSubscribe($user->id, $club->id);
                }
            } 
            
        } else if(!empty($data['source']) && $data['source'] == 'single') {

            $msg = $this->saveSubscribe(Auth()->user()->id, $data['clubId']); 

        } else {

            $checkClubUser = ClubUser::where('user_id', Auth()->user()->id)
                                        ->where('club_id', $data['clubId'])
                                        ->first();

            if($checkClubUser !== null) {
                $checkClubUser->delete();
                $msg = "User(s) has been unsubscribed to Club(s).";
            }
        }

        return response()->json(array('success'=> $msg, 'ajaxData' => $data['source']), 200); 
    } 

    /**
     * Store a new entry.
     */
    public function saveSubscribe($user_id, $club_id) {

        $checkClubUser = ClubUser::where('user_id', $user_id)
                                    ->where('club_id', $club_id)
                                    ->first(); 

        if($checkClubUser === null) {
            ClubUser::create([
                'id'      =>  null,
                'user_id' => $user_id,
                'club_id' => $club_id,
                'assigned_by'  => Auth()->user()->id
            ]);
            return "User(s) has been subscribe to Club(s).";
        } else {  
            return "User(s) has already been subscribe to Club(s).";
        }
    }

}