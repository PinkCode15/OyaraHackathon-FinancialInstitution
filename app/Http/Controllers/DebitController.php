<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TransactionRequest;
use App\Customer;
use App\CustomerBalance;
use App\Transaction;


class DebitController extends Controller
{
    public function debitCustomer(TransactionRequest $request) {
        $charge = 0;
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
            if($customer->customerBalance->available_balance < $request->amount ){
                return response()->json([
                    'status' => 'failed',
                    'statuscode' => '08',
                    'message' => 'Insufficient Funds'    
                ]);
            }

            $channel = strtolower($request->channel);
            if ($channel == 'pos'){
                $charge = ((0.75 / 100) * $request->amount) ; 
                if ($charge > 1200){
                    $charge = 1200;
                }
            }
            elseif($channel == 'atm'){
                if(Transaction::countMonthTransactions($request->account_number) > 3){
                    $charge = 35;
                }
            }
            elseif($channel == 'e-channels'){
                if ($request->amount < 5000){
                    $charge = ((5 / 100) * $request->amount) ; 
                    if ($charge > 10){
                        $charge = 10;
                    } 
                }
                else if ($request->amount < 50000 && $request->amount < 5000  ){
                    $charge = ((4.5 / 100) * $request->amount) ; 
                    if ($charge > 25){
                        $charge = 25;
                    } 
                }
                else if ($request->amount > 50000  ){
                    $charge = ((3 / 100) * $request->amount) ; 
                    if ($charge > 50){
                        $charge = 50;
                    } 
                }
            }

            $total_amount = $request->amount + $charge;
            
            $customerBalance = CustomerBalance::getCustomerBalance($request->account_number);
            $customerBalance->available_balance = $customerBalance->available_balance - $total_amount;
            $customerBalance->save();

            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'account_number' => $request->account_number,
                'amount' => $request->amount,
                'channel' => strtolower($request->channel),
                'debit_or_credit' => 'Debit',
                'narration' => $request->narration,
                'reference_id' => strtoupper($request->reference),
                'transaction_type' => 'Debit',
                'balance_after' => $customerBalance->available_balance,
                'value_date' => date('Y-m-d')
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'statuscode' => '02',
                'message' => 'Account Debited Successfully'    
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
