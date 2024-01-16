<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BotProfile;

class BotOverviewController extends Controller
{
    public function index() {

        $post_per_page = setting('admin.notification_per_page');
        $overview = BotProfile::with('machine', 'club')->get(); 

        return view('/vendor/voyager/bot/overview', compact('overview'));
    }

    public function socket() {
        dd('Work in Progress! ');

        //Get BotProfile by id and toggle the status from stop to start and vice versa
        // $BotProfile = BotProfile::where('id', \request("id"))->first();

        // dd($BotProfile->toArray());
        // $BotProfile->status = $BotProfile->status=="1"?"start":"stop";
        // $BotProfile->save();
        // return redirect(route('voyager.posts.index'));
    }
}
