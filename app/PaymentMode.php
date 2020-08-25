<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMode extends Model
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
     * Get the user that owns the paymentMode.
     */
    public function user()
    {
        return $this->belongsTo('App\Book');
    }

    /**
     * Get the transactions that owns by the paymentMode.
     */
    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    /**
     * Get the services that owns by the paymentMode.
     */
    public function services()
    {
        return $this->hasMany('App\Service', 'payment_mode_id');
    }
}
