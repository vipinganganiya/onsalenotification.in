         /* function onSaleNotificationPage() */
         1
        $post_per_page = setting('admin.notification_per_page');
        if(Auth::user()->id == 1 && Auth::user()->email == 'admin@admin.com') {

            $listings = Event::whereDate('onsale_date', '>=', Carbon::today())->orderBy('onsale_date','asc')->paginate($post_per_page);   

        } else { 
            
            $clubs = Club::where('assign_ids', Auth::user()->id)->get();  
            if(($clubs) && count($clubs) > 0) { 
                $clubIDs = array();
                foreach($clubs as $club) {
                    array_push($clubIDs, $club->id);
                }
            }

            $listings = Event::WhereIn('club_id', $clubIDs)->whereDate('onsale_date', '>=', Carbon::today())->orderBy('onsale_date','asc')->paginate($post_per_page); 
        }

        2
        $post_per_page = setting('admin.notification_per_page');

        
        $clubs = Club::assignTo()->get(); 

        if(($clubs) && count($clubs) > 0) { 
            $clubIDs = array();
            foreach($clubs as $club) {
                array_push($clubIDs, $club->id);
            }
        } 

        $listings = Event::clubs($clubIDs)->today()->paginate($post_per_page);

        return view('/vendor/voyager/notification/browse', compact('listings'));

        3
        $listings = Club::assignTo()->with(['events' => function($q) {
            $q->today();
        }])->get();

        return view('/vendor/voyager/notification/browse', compact('listings'));


        /* function filterUpcommingSale(Request $request) */

        $data = $request->all();
        $filterValue = $data['filter'];
        $todayDate = Carbon::now();
        $pastRecords = Carbon::today()->subDays(1);
        $post_per_page = setting('admin.notification_per_page');
        $upDay = Carbon::now()->addDays(2);
        $nxtWeek = Carbon::now()->addWeeks(1);
        $nxtMonth = Carbon::now()->addMonths(1);

        if(Auth::user()->id == 1 && Auth::user()->email == 'admin@admin.com') {

            if($filterValue == 'upTo2Days') { 
                $listings = Event::whereBetween('onsale_date',[$todayDate->toDateString(), $upDay->toDateString()])
                                    ->paginate($post_per_page);
            } else if($filterValue == 'nxtWeek') {
                
                $listings = Event::whereBetween('onsale_date',[$todayDate->toDateString(), $nxtWeek->toDateString()])
                                    ->orderBy('onsale_date','asc')
                                    ->paginate($post_per_page);
            } else if($filterValue == 'nxtMonth') { 
                $listings = Event::whereBetween('onsale_date',[$todayDate->toDateString(), $nxtMonth->toDateString()])
                                    ->orderBy('onsale_date','asc')
                                    ->paginate($post_per_page);
            } else if($filterValue == 'past') { 
                $listings = Event::whereDate('onsale_date', '<', Carbon::today())
                                    ->orderBy('onsale_date','asc')
                                    ->paginate($post_per_page);
            } else if($filterValue == 'seeAll') { 
                $listings = Event::orderBy('onsale_date', 'asc')
                                    ->paginate($post_per_page);
            } else if($filterValue == 'onLoadData') {

                /**
                 *  Default data Listing + Pagination
                 */
                $listings = Event::whereDate('onsale_date', '>=', Carbon::today())->orderBy('onsale_date','asc')->paginate($post_per_page); 
            } else {

                /**
                 * When User Click specific date 
                 * from calender 
                 * below condition works;
                 */
                $listings = Event::whereDate('onsale_date', '=', $filterValue)
                                    ->orderBy('onsale_date')
                                    ->paginate($post_per_page);
            }

         } else { 

            $clubs = Club::where('assign_ids', Auth::user()->id)->get();  
            if(($clubs) && count($clubs) > 0) { 
                $clubIDs = array();
                foreach($clubs as $club) {
                    array_push($clubIDs, $club->id);
                }
            } 

            if($filterValue == 'upTo2Days') {
                
                $listings = Event::WhereIn('club_id', $clubIDs)->whereBetween('onsale_date',[$todayDate->toDateString(), $upDay->toDateString()])
                                    ->paginate($post_per_page);
            } else if($filterValue == 'nxtWeek') { 
                $listings = Event::WhereIn('club_id', $clubIDs)->whereBetween('onsale_date',[$todayDate->toDateString(), $nxtWeek->toDateString()])
                                    ->orderBy('onsale_date','asc')
                                    ->paginate($post_per_page);
            } else if($filterValue == 'nxtMonth') { 
                $listings = Event::WhereIn('club_id', $clubIDs)->whereBetween('onsale_date',[$todayDate->toDateString(), $nxtMonth->toDateString()])
                                    ->orderBy('onsale_date','asc')
                                    ->paginate($post_per_page);
            } else if($filterValue == 'past') { 
                $listings = Event::WhereIn('club_id', $clubIDs)->whereDate('onsale_date', '<', Carbon::today())
                                    ->orderBy('onsale_date','asc')
                                    ->paginate($post_per_page);
            } else if($filterValue == 'seeAll') { 
                $listings = Event::WhereIn('club_id', $clubIDs)->orderBy('onsale_date', 'asc')
                                    ->paginate($post_per_page);
            } else if($filterValue == 'onLoadData') {

                /**
                 *  Default data Listing + Pagination
                 */
                $listings = Event::WhereIn('club_id', $clubIDs)->whereDate('onsale_date', '>=', Carbon::today())->orderBy('onsale_date','asc')->paginate($post_per_page); 
            } else {

                /**
                 * When User Click specific date 
                 * from calender 
                 * below condition works;
                 */
                $listings = Event::WhereIn('club_id', $clubIDs)->whereDate('onsale_date', '=', $filterValue)
                                    ->orderBy('onsale_date')
                                    ->paginate($post_per_page);
            }
        }

        /*ClubController*/

         //$this->authorize('browse_subscribe');
        // if(Auth::user()->hasRole('admin')) {
        //     $clubs = Club::orderBy('id', 'asc')->paginate(10);  
        // } else {
        //     $clubs = Club::where('author_id', Auth::user()->id)->orderBy('id', 'asc')->paginate(10);  
        // } 