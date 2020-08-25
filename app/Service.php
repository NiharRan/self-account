<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'amount', 'paid', 'due', 'sector_id', 'payment_mode_id', 'user_id', 'serve_at'
    ];

    /**
     * Get the user that owns the service.
     */
    public function user()
    {
        return $this->belongsTo('App\Book');
    }

    /**
     * Get the sector that owns the service.
     */
    public function sector()
    {
        return $this->belongsTo('App\Sector');
    }


    /**
     * Get the paymentMode that owns the service.
     */
    public function payment_mode()
    {
        return $this->belongsTo('App\PaymentMode', 'payment_mode_id');
    }

    /**
     * Get the transactions that owns by the user.
     */
    public function transactions()
    {
        return $this->hasMany('App\Transaction', 'transaction_number');
    }
}
