<?php
    namespace App\Widgets;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Str;
    use TCG\Voyager\Facades\Voyager;
    use Arrilot\Widgets\AbstractWidget; 
    use Illuminate\Support\Facades\DB;
    use App\Http\Controllers\Admin\Event;  

    class EventDimmer extends AbstractWidget {

        protected $config = [];

        public function run() {
            //$count = DB::table('events')->count();;
            $count = \App\Models\Event::count();;
            $string = trans_choice('Events', $count);

            return view('voyager::dimmer', array_merge($this->config, [
                'icon'   => 'voyager-bag',
                'title'  => "{$count} {$string}",
                'text'   => __('voyager::dimmer.post_text', ['count' => $count, 'string' => Str::lower($string)]),
                'button' => [
                    'text' => __('View All Events'),
                    'link' => route('voyager.events.index'),
                ],
                'image' => voyager_asset('images/widget-backgrounds/02.jpg'),
            ]));
        }

        public function shouldBeDisplayed() { 
            //return Auth::user()->can('browse', \App\Models\Event::class);
            return auth()->user()->hasRole('admin');
        }
 }