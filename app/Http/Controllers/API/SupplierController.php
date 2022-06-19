<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Supplier;

class SupplierController extends Controller
{
    public function index(){
        try {
            $supplier = Supplier::all();
            
            return response()->json(
                [
                    'data' => $supplier
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

    public function storeData(Request $request){
        if($request->id){
            $supplier = Supplier::find($request->id);
        } else {
            $supplier = new Supplier();
        }
        $supplier->store_id = 1;
        $supplier->supplier_name = $request->supplier_name;
        $supplier->supplier_phone = $request->supplier_phone;
        $supplier->supplier_address = $request->supplier_address;
        $supplier->save();
    }

    public function create(Request $request){
        $validatedData = Validator::make($request->all(), [
            'supplier_name' => 'required|max:255',
            'supplier_phone' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'data' => [
                    'error' => $validatedData->errors()
                ]
            ], 400);
        }

        try {
            $this->storeData($request);

            return response()->json(
                [
                    'data' => 'Inserted successfully'
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

    public function edit(Request $request){
        $validatedData = Validator::make($request->all(), [
            'supplier_name' => 'required|max:255',
            'supplier_phone' => 'required',
            'id' => 'required'
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'data' => [
                    'error' => $validatedData->errors()
                ]
            ], 400);
        }

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

    public function delete($id){
        try {
            $supplier = Supplier::find($id);
            $supplier->delete();

            return response()->json(
                [
                    'data' => 'Deleted Successfully'
                ],
                200
            );
        } catch(\Throwable $th){
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

    public function getItem($id){
        try {
            $supplier = Supplier::find($id);

            return response()->json(
                [
                    'data' => $supplier
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
}
