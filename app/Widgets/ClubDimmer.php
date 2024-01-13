<?php
  namespace App\Widgets;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Str;
  use TCG\Voyager\Facades\Voyager;
  use Arrilot\Widgets\AbstractWidget;
  use App\Controller\HostingClub;
  use Illuminate\Support\Facades\DB;
  use App\Http\Controllers\Admin;
  
  class ClubDimmer extends AbstractWidget
  {

     protected $config = [];

     public function run()
     {
       $count = DB::table('hosting_clubs')->count();;
       $string = trans_choice('Hosting Clubs', $count);

       return view('voyager::dimmer', array_merge($this->config, [
        'icon'   => 'voyager-star',
        'title'  => "{$count} {$string}",
        'text'   => __('voyager::dimmer.post_text', ['count' => $count, 'string' => Str::lower($string)]),
        'button' => [
            'text' => __('View All Clubs'),
            'link' => route('voyager.hosting-clubs.index'),
        ],
        'image' => voyager_asset('images/widget-backgrounds/02.jpg'),
      ]));
   }

   public function shouldBeDisplayed() {
        // return Auth::user()->can('browse', HostingClub::class);
        return Auth::user()->can('browse', Voyager::model('Page'));
   }
 }