<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Category;


class CategoryController extends Controller
{
    public function index(){
        try {
            $category = Category::all();
            
            return response()->json(
                [
                    'data' => $category
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
            $category = Category::find($request->id);
        } else {
            $category = new Category();
        }
        $category->store_id = 1;
        $category->category_name = $request->category_name;
        $category->added_by = $request->added_by;
        $category->description = $request->description;
        $category->save();
    }

    public function create(Request $request){
        $validatedData = Validator::make($request->all(), [
            'category_name' => 'required|max:255',
            'added_by' => 'required',
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
            'category_name' => 'required|max:255',
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
            $category = Category::find($id);
            $category->delete();

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
            $category = Category::find($id);

            return response()->json(
                [
                    'data' => $category
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
