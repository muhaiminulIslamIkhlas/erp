<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Brand;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index(Request $request){
        try {
            $brand = Brand::orderBy('id', 'desc')->paginate($request->get('perPage'), ['brand_name','description','id'], 'page');
            
            return response()->json(
                [
                    'data' => $brand
                ], 200
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
            $brand = Brand::find($request->id);
        } else {
            $brand = new Brand();
        }
        $brand->store_id = 1;
        $brand->brand_name = $request->brand_name;
        $brand->added_by = "Admin";
        $brand->description = $request->description;
        $brand->save();
    }

    public function create(Request $request){
        $validatedData = Validator::make($request->all(), [
            'brand_name' => 'required|max:255'
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
            'brand_name' => 'required|max:255',
            'added_by' => 'required',
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
            $brand = Brand::find($id);
            $brand->delete();

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
            $brand = Brand::find($id);

            return response()->json(
                [
                    'data' => $brand
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
