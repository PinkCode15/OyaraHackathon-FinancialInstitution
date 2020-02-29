<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = ['customer_id','account_number', 'amount','channel','debit_or_credit','narration',
                            'reference_id','transaction_type','balance_after','value_date'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public static function checkIfReferenceExists($reference){
        return self::where('reference_id',$reference)->exists();
    }

    public static function countMonthTransactions($account_number){
        return self::where('account_number',$account_number)->whereMonth('created_at', now()->month)->
        where('transaction_type','Debit')->count();
    }

    public static function getStatement($data){
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $account_number = $data['account_number'];
        $channel = $data['channel'];
        $reference = $data['reference'];
        
        return self::where('account_number',$account_number)->whereBetween('value_date',[$start_date, $end_date])->where
        ('channel',$channel)->where('reference_id',$reference)->get(['account_number','amount','channel','reference_id',
        'transaction_type','balance_after','created_at']);
        
    }

}
