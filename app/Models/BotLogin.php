<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotLogin extends Model
{
    use HasFactory;

    protected $table = 'bot_logins';
    /**
     * Fillable fields for a Profile.
     *
     * @var array
     */
    protected $fillable = [
        'club_id',
        'username',
        'password',
        'members_count',
        'avail_members_count',
        'description',
    ];

    public function loginProfile() {
        return $this->belongsTo(BotProfile::class);
    }

    public function loginClubs() {
        return $this->hasMany(BotClub::class);
    }
}
