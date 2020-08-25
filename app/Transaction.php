<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount', 'transaction_number', 'transaction_type_id', 'user_id'
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo('App\Book');
    }

    /**
     * Get the service that owns the transaction.
     */
    public function service()
    {
        return $this->belongsTo('App\Service', 'transaction_number');
    }

    /**
     * Get the transactionType that owns the transaction.
     */
    public function transactionType()
    {
        return $this->belongsTo('App\TransactionType');
    }


}
