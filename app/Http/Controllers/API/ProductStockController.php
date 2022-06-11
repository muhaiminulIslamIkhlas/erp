<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class ProductStockController extends Controller
{
    public function storeData(Request $request)
    {
        if($request->id) {
            $productStock = ProductStock::find($request->id);
        }
        else {
            $productStock = new ProductStock();
        }

        $productStock->qty = $request->qty;
        $productStock->buying_price = $request->buying_price;
        $productStock->selling_price = $request->selling_price;
        $productStock->product_id = $request->product_id;

        $productStock->save();
    }

    public function index()
    {
        try {
            $productStock = ProductStock::all();

            return response()->json([
                'data' => $productStock
            ], 202);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

    public function getById($id)
    {
       try {
        $productStock = ProductStock::find($id);

        return response()->json([
            'data' => $productStock
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
            'message' => 'Product Stock Saved Successfully'
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
                'message' => 'Product Stock Updated Successfully'
            ], 200);
           } catch (\Throwable $th) {
            return response()->json($th, 500);
           }
    }

    public function delete($id)
    {
        try {
            $productStock = ProductStock::find($id);
            $productStock->delete();

            return response()->json([
                'message' => 'Product Stock Deleted Successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }

    }
}
