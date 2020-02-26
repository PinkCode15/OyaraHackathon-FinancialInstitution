<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\FreezeRequest;
use App\Customer;

class FreezeController extends Controller
{
    public function FreezeCustomer(FreezeRequest $request) {
        DB::beginTransaction();
        try{
            if(!Customer::checkIfCustomerExists($request->account_number)){
                return response()->json([
                    'status' => 'failed',
                    'statuscode' => '06',
                    'message' => 'Customer Does Not Exist'    
                ]);
            }
            if(Customer::checkStatus($request->account_number) == 'inactive'){
                return response()->json([
                    'status' => 'failed',
                    'statuscode' => '07',
                    'message' => 'Account Is Already Inactive'    
                ]);
            }
            $customer = Customer::getCustomer($request->account_number);
            $customer->status = 'inactive';
            $customer->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'statuscode' => '02',
                'message' => 'Account Freeze Successful'    
            ]);

            
        }
        catch(Exception $e){
            DB::rollback();

            return response()->json([
                'status' => 'failed',
                'statuscode' => '05',
                'message' => 'Server Error'    
            ]);
        }
    }
}
