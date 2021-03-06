<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = ['account_number', 'account_name','currency','account_type','bvn','full_name',
                            'email','phone_number','status'];

    public function customerBalance(): HasOne
    {
        return $this->hasOne(CustomerBalance::class);
    }

    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public static function getCustomer($account_number){
        return self::where('account_number',$account_number)->first();
    }

    public static function checkIfCustomerExists($account_number){
        return self::where('account_number',$account_number)->exists();
    }
    public static function checkStatus($account_number){
        return self::where('account_number',$account_number)->first()->status;
    }
}
