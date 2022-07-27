<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function storeData(Request $request)
    {
        if ($request->id) {
            $customer = Customer::find($request->id);
        } else {
            $customer = new Customer();
        }

        $customer->customer_name = $request->customer_name;
        $customer->customer_phone = $request->customer_phone;
        $customer->customer_address = $request->customer_address;
        $customer->added_by = "Admin";
        $customer->store_id = 1;

        $customer->save();
    }

    public function index(Request $request)
    {
        try {

            $customer = Customer::orderBy('id', 'desc')->paginate($request->get('perPage'), ['customer_name', 'customer_phone', 'customer_address', 'id'], 'page');

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
                'data' => [
                    'message' => 'Customer Added Successfully'
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'data' => [
                        'error' => $th->getMessage()
                    ]
                ],
                500
            );
        }
    }

    public function update(Request $request)
    {
        try {

            $this->storeData($request);

            return response()->json(
                [
                    'data' => 'Updated successfully'
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'data' => [
                        'error' => $th->getMessage()
                    ]
                ],
                500
            );
        }
    }

    public function delete($id)
    {
        try {
            $customer = Customer::find($id);
            $customer->delete();

            return response()->json([
                'data' => [
                    'message' => 'Customer Deleted Successfully'
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'data' => [
                        'error' => $th->getMessage()
                    ]
                ],
                500
            );
        }
    }

    public function getAll()
    {
        try {
            $brand = Customer::select('customer_name', 'customer_phone', 'id')->orderBy('id', 'desc')->where('store_id', 1)->get()->map->formatSelect();
            return response()->json(
                [
                    'data' => $brand
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'error' => $th->getMessage()
                ],
                500
            );
        }
    }
}
