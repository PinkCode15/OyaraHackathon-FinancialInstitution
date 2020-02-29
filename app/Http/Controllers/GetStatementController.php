<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GetStatementRequest;
use App\Transaction;
use App\Customer;

class GetStatementController extends Controller
{
    public function getStatement(GetStatementRequest $request) {
        try{
            $data = [
                'account_number' => $request->account_number,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reference' => strtoupper($request->reference),
                'channel' => strtolower($request->channel),
            ];

            $return_data = Transaction::getStatement($data); 
           
            return response()->json([
                'status' => 'success',
                'statuscode' => '02',
                'message' => 'Transaction Retrieved Successfully',
                'data' => $return_data    
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'failed',
                'statuscode' => '05',
                'message' => 'An Error Occured. Please Try Again.'   
            ]);
        }
    }

}
