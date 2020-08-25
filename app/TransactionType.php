<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_id',
    ];


    /**
     * Get the user that owns the transaction type.
     */
    public function user()
    {
        return $this->belongsTo('App\Book');
    }
}
