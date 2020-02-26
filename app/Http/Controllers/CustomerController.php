<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateCustomerRequest;
use App\Customer;
use App\CustomerBalance;

class CustomerController extends Controller
{
    public function createCustomer(CreateCustomerRequest $request) {
        DB::beginTransaction();
        try{
            if(Customer::checkIfCustomerExists($request->account_number)){
                return response()->json([
                    'status' => 'failed',
                    'statuscode' => '04',
                    'message' => 'Customer Already Exists'    
                ]);
            }

            $customer = Customer::create([
                'account_number' => $request['account_number'],
                'account_name' => $request['account_name'],
                'currency' => $request['currency'],
                'account_type' => $request['account_type'],
                'bvn' => $request['bvn'],
                'full_name' => $request['full_name'],
                'email' => $request['email'] ?? null,
                'phone_number' => $request['phone_number'] ?? null
            ]);

            $customerBalance = CustomerBalance::create([
                'customer_id' => $customer->id,
                'account_number' => $request['account_number'],
                'currency' => $customer->currency
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'statuscode' => '02',
                'message' => 'Customer Created Successfully'
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
