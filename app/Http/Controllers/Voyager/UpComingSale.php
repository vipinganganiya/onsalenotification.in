<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Club;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon; 
use TCG\Voyager\Facades\Voyager;
use Excel;
use Illuminate\Support\Facades\Auth;
//use App\Imports\ImportOnSaleNotification;
//use App\Exports\ExportOnSaleNotification;


class UpComingSale extends Controller
{
    /**
     * Get all hosting club listing
     * Using Scopes
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index() {
        /**
         *  Notification Per Page = Limit
         */
        $post_per_page = setting('admin.notification_per_page'); 

        $listings = Event::upTo2DaysEvent()->paginate($post_per_page);
        return view('/vendor/voyager/notification/browse', compact('listings')); 
    } 

    /**
     * Display with the specified filters.
     * 
     * @param  int  $data
     * @return \Illuminate\Http\Response
     */
    public function filterUpcommingSale(Request $request) {

        $data = $request->all();
        $filterValue = $data['filter'];
        $post_per_page = setting('admin.notification_per_page'); 

        $listings = Event::currentUserClubs(); 

        switch($filterValue) {

            case('upTo2Days'):
                $listings = $listings->upTo2DaysEvent();
                break;

            case('nxtWeek'):
                $listings = $listings->nextWeekEvent();
                break;

            case('nxtMonth'): 
                 $listings = $listings->nextMonthEvent();
                break;

            case('past'): 
                $listings = $listings->pastEvents();
                break;

            case('seeAll'):
                $listings = $listings->SeeAll();
                 //dd($listings->toSql(), $listings->getBindings());
                break;

            case('onLoadData'):
                 /**
                 *  Default data Listing + Pagination
                 */
                $listings = $listings->onLoadEvent(); 
                break;

            default:

                /**
                 * When User Click specific date 
                 * from calender 
                 * below condition works;
                 */
                $listings = $listings->FilterDateEvent($filterValue);
        }   

        $listings = $listings->paginate($post_per_page);

        $next = $data['page']+1;

        $html =  view('/vendor/voyager/notification/card-filter', [
                        'listings'=> $listings, 
                        'next' => $next, 
                        'filterValue' => $filterValue]
                    )->render();
        
        return response()->json(
                    array(
                        'html'=> $html, 
                        'next' => $next, 
                        'currentPage' => $listings->currentPage(), 
                        'totalListing' => $listings->total(), 
                        'lastPage' => $listings->lastPage()
                    ), 200);  
    } 

    /**
     * Display the specified resource.
     * 
     * @param  int  $data
     * @return \Illuminate\Http\Response
     */
    public function filterDate(Request $request) { 
        $data = $request->all(); 

        $c = Event::whereDate('onsale_date', '=', $data['_dfilter'])->orderBy('onsale_date','asc')->count(); 

        $event_d =  Carbon::parse($data['_dfilter'])->format('d');
        $event_m =  Carbon::parse($data['_dfilter'])->format('m');
        $event_y =  Carbon::parse($data['_dfilter'])->format('y');

        return response()->json(array('_fd'=>  $data['_dfilter'], '_d'=>  $event_d, '_m'=>  $event_m, '_c' => $c), 200); 
    }

    public function importOnSaleNotification(Request $request) { 

        $this->validate($request, ['select_file'  => 'required|mimes:xls,xlsx']);
        $path = $request->file('select_file')->getRealPath();  
        $data = Excel::import(new ImportOnSaleNotification, $request->file('select_file')); 
        return back()->with('success', 'Excel Data Imported successfully.');
    }

    public function exportOnSaleNotification(Request $request) { 

         return Excel::download(new ExportOnSaleNotification, 'onsalenotification.xlsx');
    }
}
