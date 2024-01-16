<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class BotProfile extends Model
{
    use HasFactory;

    protected $table = 'bot_profiles';
    /**
     * Fillable fields for a Profile.
     *
     * @var array
     */
    protected $fillable = [
        'profile_name',
        'mac_id',
        'club_id',
        'proxy_id',
        'login_id',
        'event_name',
        'event_url',
    ];

    public function club() { 
        return $this->hasOne(BotClub::class, 'id', 'club_id');
    }

    public function login() { 
        return $this->hasOne(BotLogin::class, 'id', 'proxy_id');
    }

    public function machine() { 
        return $this->hasOne(BotMachine::class, 'id', 'mac_id');
    }

    public function proxy() { 
        return $this->hasOne(BotProxy::class, 'id', 'proxy_id');
    }
}
