<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Club;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Traits\Resizable;

class Event extends Model
{
    use HasFactory, SoftDeletes, Resizable;

    protected $dates = ['deleted_at'];

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

     /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true; 

    /**
     * Fillable fields for a Profile.
     *
     * @var array
     */
    protected $fillable = [
        'title', 
        'club_id',
        'date', 
        'time',
        'onsale_date', 
        'onsale_time',
        'criteria', 
        'URL', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
         'created_by',
    ];
    
    /**
     * The attributes which first letter must be upper case;
     */
    public function setTitleAttribute($value) {
        $this->attributes['title'] = ucwords($value);
    }

    /**
     * Many-to-Many relations with User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */ 

    /**
     * The users that belong to the role.
     */
    public function club()
    { 
        return $this->belongsTo(Club::class, 'club_id', 'id'); //->using(Event::class);
    }  

    /**
     * Scope created a while ago events n after
     */
    public function scopeSeeAll($query) { 
       return $query->whereDate('onsale_date', '>=', Carbon::today())->orderBy('onsale_date', 'asc')->orderBy('onsale_time','asc');
    }

    /**
     * The users that belong to the role.
     */
    public function scopeCurrentUserClubs($query) {  

        $is_admin = Auth::user()->hasRole('admin'); 
        $clubIDs = array();

        if($is_admin === false) { 
            $clubIDs = ClubUser::where('user_id', Auth()->user()->id)->pluck('club_id')->toArray();
        }

        return $is_admin ? $query : $query->WhereIn('club_id', $clubIDs);
    } 

    /**
     * Scope created for upto 2 days events
     */
    public function scopeUpTo2DaysEvent($query) {

        $todayDate = Carbon::now();
        $upDay = Carbon::now()->addDays(2);

        return $query->whereBetween('onsale_date',[$todayDate->toDateString(), $upDay->toDateString()])->orderBy('onsale_date','asc')->orderBy('onsale_time','asc');;
    }

    /**
     * Scope created for next week events
     */
    public function scopeNextWeekEvent($query) {

        $todayDate = Carbon::now();
        $nxtWeek = Carbon::now()->addWeeks(1);

        return $query->whereBetween('onsale_date',[$todayDate->toDateString(), $nxtWeek->toDateString()])->orderBy('onsale_date','asc')->orderBy('onsale_time','asc');;
    }

    /**
     * Scope created for next month events
     */
    public function scopeNextMonthEvent($query) {

        $todayDate = Carbon::now();
        $nxtMonth = Carbon::now()->addMonths(1);

        return $query->whereBetween('onsale_date',[$todayDate->toDateString(), $nxtMonth->toDateString()])->orderBy('onsale_date','asc');
    }

    /**
     * Scope created for past/old events
     */
    public function scopePastEvents($query) { 

        return $query->whereDate('onsale_date', '<', Carbon::today())->orderBy('onsale_date','asc')->orderBy('onsale_time','asc');;
    }

    /**
     * Scope created for All events
     */
    public function scopeSeeAllEvent($query) { 

        return $query->orderBy('onsale_date', 'asc')->orderBy('onsale_time','asc');;
    }

    /**
     * Scope created for OnLoad/Paginate events
     */
    public function scopeOnLoadEvent($query) { 

        return $query->whereDate('onsale_date', '>=', Carbon::today())->orderBy('onsale_date','asc')->orderBy('onsale_time','asc');;
    }

    /**
     * Scope created for filter date/specific date events
     */
    public function scopeFilterDateEvent($query, $args) { 

        return $query->whereDate('onsale_date', '=', $args)->orderBy('onsale_date')->orderBy('onsale_time','asc');;
    }

    /**
     *  Save created_by ID same as User ID
     *  If no author has been assigned,
     *  assign the current user's id as 
     *  the creator of the event
     */
    public function save(array $options = []) { 

        if (!$this->created_by && Auth::user()) {
            $this->created_by = Auth::user()->getKey();
        }

        return parent::save();
    }
    
    /**
     *  Event will be visible to user's only
     */
    public function scopeEventCurrentUser($query)
    {
        $is_admin = Auth::user()->hasRole('admin'); 
        if($is_admin === false) { 
            return $query->where('created_by', Auth::user()->id);
        }
    }
}
