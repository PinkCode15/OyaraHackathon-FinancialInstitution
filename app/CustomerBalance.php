<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class CustomerBalance extends Model
{
    protected $fillable = ['customer_id','account_number', 'available_balance','cleared_balance','unclear_balance',
                            'hold_balance','minimum_balance','currency'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public static function getCustomerBalance($account_number){
        return self::where('account_number',$account_number)->first();
    }

}
