<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Club;

class User extends \TCG\Voyager\Models\User
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function club() { 
    //     return $this->hasMany(Club::class, 'user_id', 'id');
    // } 

    /**
     * The roles that belong to the user.
     */
    public function clubs() {
        return $this->belongsToMany(Club::class, 'club_user', 'user_id', 'club_id')->whereNull('club_user.deleted_at');
    }

    public function clubsPivot() {
        return $this->hasManyThrough(
            // required
            'App\Models\Club', // the related model
            'App\Models\ClubUser', // the pivot model

            // optional
            'user_id', // the current model id in the pivot
            'id', // the id of related model
            'id', // the id of current model
            'club_id' // the related model id in the pivot
        );
    }
}
