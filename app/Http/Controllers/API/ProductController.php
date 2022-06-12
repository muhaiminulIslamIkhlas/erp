<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::all();

        // return new ProductResource(Product::all());

        return response()->json([
            'data' => $product
        ], 202);
    }

    public function getById($id)
    {
        $product = Product::find($id);
        return response()->json([
            'data' => $product
        ], 200);
    }

    public function storeData(Request $request)
    {
        if ($request->id) {
            $product = Product::find($request->id);
        } else {
            $product = new Product();
        }
        $product->product_name = $request->product_name;
        $product->company_name = $request->company_name;
        $product->size = $request->size;
        $product->color = $request->color;
        $product->unit_id = $request->unit_id;
        $product->save();
    }

    public function store(Request $request)
    {
        try {
            $this->storeData($request);
            return response()->json([
                'message' => 'Product saved successfully'
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
                'message' => 'Product updated successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::find($id);
            $product->delete();
            return response()->json([
                'message' => 'Product deleted successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function productSave(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_name' => 'required',
                'company_name' => 'required',
                'size' => 'required',
                'color' => 'required',
                'unit_id' => 'required',
                'qty' => 'required',
                'buying_price' => 'required',
                'selling_price' => 'required',
            ]);

            $product = new Product();
            $product->product_name = $request->product_name;
            $product->company_name = $request->company_name;
            $product->size = $request->size;
            $product->color = $request->color;
            $product->unit_id = $request->unit_id;
            $product->save();

            $productStock = new ProductStock();
            $productStock->product_id = $product->id;
            $productStock->qty = $request->qty;
            $productStock->buying_price = $request->buying_price;
            $productStock->selling_price = $request->selling_price;
            $productStock->save();

            return response()->json([
                'message' => 'Product information saved successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }
}
