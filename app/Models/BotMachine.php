<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotMachine extends Model
{
    use HasFactory;

    protected $table = 'bot_machines';
    /**
     * Fillable fields for a Profile.
     *
     * @var array
     */
    protected $fillable = [
        'machine_unique_field',
        'machine_name',
        'ram',
        'graphics',
        'processor',
        'location',
    ];

    /**
     * Get the user that owns the PoTixstockListingHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function machine() {
        return $this->belongsTo(BotProfile::class, 'mac_id', 'id');
    }
}
