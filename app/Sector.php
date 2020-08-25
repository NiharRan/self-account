<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_id'
    ];

    /**
     * Get the user that owns the sector.
     */
    public function user()
    {
        return $this->belongsTo('App\Book');
    }

    /**
     * Get the transactions that owns by the sector.
     */
    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }
}
