<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotThread extends Model
{
    use HasFactory;

    protected $table = 'bot_threads';
    /**
     * Fillable fields for a Profile.
     *
     * @var array
     */
    protected $fillable = [
        'profile_id',
        'thread_no',
        'event_name',
        'status',
        'block',
        'seat_row',
        'booked_seat',
        'no_of_seats',
    ];

    public function profile() {
        return $this->belongsTo(BotProfile::class, 'profile_id', 'id');
    }
}
