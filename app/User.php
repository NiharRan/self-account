<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the role that owns the transaction.
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    /**
     * Get the sectors that owns by the user.
     */
    public function sectors()
    {
        return $this->hasMany('App\Sector');
    }

    /**
     * Get the paymentModes that owns by the user.
     */
    public function paymentModes()
    {
        return $this->hasMany('App\PaymentMode');
    }

    /**
     * Get the transactionTypes that owns by the user.
     */
    public function transactionTypes()
    {
        return $this->hasMany('App\TransactionType');
    }

    /**
     * Get the transactions that owns by the user.
     */
    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    /**
     * Get the services that owns by the user.
     */
    public function services()
    {
        return $this->hasMany('App\Service');
    }
}
