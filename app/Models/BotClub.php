<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotClub extends Model
{
	use HasFactory;
	
    protected $table = 'bot_clubs';
    /**
     * Fillable fields for a Profile.
     *
     * @var array
     */
    protected $fillable = [
        'club_name',
        'slug',
        'image',
        'description'
    ];

    public function clubProfile() {
        return $this->belongsTo(BotProfile::class);
    }

    public function clubLogin() {
        return $this->belongsTo(BotLogin::class);
    }
}
