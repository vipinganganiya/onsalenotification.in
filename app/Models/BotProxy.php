<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotProxy extends Model
{
    use HasFactory;

    protected $table = 'bot_proxies';
    /**
     * Fillable fields for a Profile.
     *
     * @var array
     */
    protected $fillable = [
        'proxy_vendor',
        'proxy_type',
        'proxy',
        'proxy_format',
        'description',
    ];

    public function ProxyProfile() {
        return $this->belongsTo(BotProfile::class);
    }
}
