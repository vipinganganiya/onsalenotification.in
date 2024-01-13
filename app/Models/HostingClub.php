<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model; 
use TCG\Voyager\Traits\Resizable;

class HostingClub extends Model
{
    use HasFactory, SoftDeletes, Resizable;
    
    protected $dates = ['deleted_at'];

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hosting_clubs';

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
        'name',
        'logo', 
    ];

     /**
    * Get the rents of a Tenant
    */ 

    public function event() { 
        return $this->hasMany(Event::class, 'club_id', 'id');
    } 
}
