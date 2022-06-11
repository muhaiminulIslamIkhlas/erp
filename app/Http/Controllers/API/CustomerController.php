<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function storeData(Request $request)
    {
        if($request->id)
        {
            $customer = Customer::find($request->id);
        }
        else {
            $customer = new Customer();
        }

        $customer->customer_name = $request->customer_name;
        $customer->customer_phone = $request->customer_phone;
        $customer->customer_address = $request->customer_address;
        
        $customer->save();
    }

    public function index()
    {
        try {

            $customer = Customer::all();

            return response()->json([
                'data' => $customer
            ], 202);
        } catch (\Throwable $th) {
           return response()->json($th, 500);
        }
    }

    public function getById($id)
    {
        try {
            $customer = Customer::find($id);

            return response()->json([
                'data' => $customer
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

    public function store(Request $request)
    {
        try {

            $this->storeData($request);

            return response()->json([
                'message' => 'Customer Saved Successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

    public function update(Request $request)
    {
        try {

            $this->storeData($request);

            return response()->json([
                'message' => 'Customer Updated Successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

    public function delete($id)
    {
        try {
            $customer = Customer::find($id);
            $customer->delete();

            return response()->json([
                'message' => 'Customer Deleted Successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }
}
