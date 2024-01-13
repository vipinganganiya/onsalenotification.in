<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use App\Models\Event;

class Club extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clubs';

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
        'slug',
        'excerpt',
        'image', 
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
    public function setTitleAttribute($value){
        $this->attributes['title'] = ucfirst($value);
    }

     /**
    * Get the rents of a Tenant
    */ 

    public function events() { 
        return $this->hasMany(Event::class);
    } 


    public function createdBy() {
       return $this->belongsTo(Voyager::modelClass('User'), 'created_by', 'id');
    }

    /**
     * The users that belong to the role.
     */
    public function users() {
        return $this->belongsToMany(Voyager::modelClass('User'), 'club_user', 'club_id', 'user_id')->whereNull('club_user.deleted_at');
    }

    public function usersPivot() { 
        return $this->hasManyThrough(
            // required
            'App\Models\User', // the related model
            'App\Models\ClubUser', // the pivot model

            // optional
            'club_ids', // the current model id in the pivot
            'id', // the id of related model
            'id', // the id of current model
            'user_id' // the related model id in the pivot
        );
    }

    /**
     * Only created_by(user) Can see his clubs entry
     * Admin can check all clubs entry
     */
    public function scopeClubCreatedBy($query) { 
        return Auth::user()->hasRole('admin') ? $query : $query->where('created_by', Auth::user()->id);
    }

    public function scopeEvents($query) {
        return $query->with('events');                
    }

    /**
     *  Save Author ID same as User ID
     *  If no user has been assigned,
     *  assign the current user's id as 
     *  the creator of the club
     */
    public function save(array $options = []) { 

        if (!$this->created_by && Auth::user()) {
            $this->created_by = Auth::user()->getKey();
        }

        return parent::save();
    }

    /**
     * The products that belong to the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */ 
}
