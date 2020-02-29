<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TransactionRequest;
use App\Customer;
use App\CustomerBalance;
use App\Transaction;


class CreditController extends Controller
{
    public function creditCustomer(TransactionRequest $request) {
        DB::beginTransaction();
        try{
            if(!Customer::checkIfCustomerExists($request->account_number)){
                return response()->json([
                    'status' => 'failed',
                    'statuscode' => '06',
                    'message' => 'Customer Does Not Exist'    
                ]);
            }
            if(Transaction::checkIfReferenceExists($request->reference)){
                return response()->json([
                    'status' => 'failed',
                    'statuscode' => '04',
                    'message' => 'Transaction Reference Already Exists'    
                ]);
            }
            if(Customer::checkStatus($request->account_number) != 'active'){
                return response()->json([
                    'status' => 'failed',
                    'statuscode' => '07',
                    'message' => 'Account Is Not Active'    
                ]);
            }
            
            $customer = Customer::getCustomer($request->account_number);
            $customerBalance = CustomerBalance::getCustomerBalance($request->account_number);
            $customerBalance->available_balance = $customerBalance->available_balance + $request->amount;
            $customerBalance->save();

            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'account_number' => $request->account_number,
                'amount' => $request->amount,
                'channel' => strtolower($request->channel),
                'debit_or_credit' => 'Credit',
                'narration' => $request->narration,
                'reference_id' => strtoupper($request->reference),
                'transaction_type' => 'Credit',
                'balance_after' => $customerBalance->available_balance,
                'value_date' => date('Y-m-d')
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'statuscode' => '02',
                'message' => 'Account Credited Successfully'    
            ]);

            
        }
        catch(Exception $e){
            DB::rollback();

            return response()->json([
                'status' => 'failed',
                'statuscode' => '05',
                'message' => 'An Error Occured. Please Try Again.'    
            ]);
        }
    }
}
