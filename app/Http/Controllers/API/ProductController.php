<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $product = tap(Product::orderBy('id', 'desc')->paginate($request->get('perPage'), ['*'], 'page'), function ($data) {
            return $data->getCollection()->transform(function ($value) {
                return $value->format();
            });
        });

        return response()->json([
            'data' => $product
        ], 202);
    }

    public function getItem($id)
    {
        $product = Product::find($id);
        return response()->json([
            'data' => $product
        ], 200);
    }

    public function addStock(ProductStock $productStock)
    {
        $item = ProductStock::where('purchase_price', $productStock->purchase_price)->where('product_id', $productStock->product_id)->first();
        if ($item) {
            $item->stock += $productStock->stock;
            $item->save();
        } else {
            $productStock->save();
        }
    }

    public function storeData(Request $request)
    {

        if ($request->id) {
            $product = Product::find($request->id);
        } else {
            $product = new Product();
        }
        $product->product_name = $request->product_name;
        $product->brand_id = $request->brand_id;
        $product->unit_id = $request->unit_id;
        $product->category_id = $request->category_id;
        $product->size = $request->size;
        $product->color = $request->color;
        $product->purchase_price = $request->purchase_price;
        $product->selling_price = $request->selling_price;
        $product->initial_stock = $request->initial_stock;
        $product->warrenty = $request->warrenty;
        $product->guarantee = $request->guarantee;
        $product->description = $request->description;
        $product->available_for_online = 0;
        $product->store_id = 1;
        $product->save();

        $productStock = new ProductStock();
        $productStock->product_id = $product->id;
        $productStock->purchase_price = $request->purchase_price;
        $productStock->stock = $request->initial_stock;
        $productStock->selling_price = $request->selling_price;
        $this->addStock($productStock);
    }

    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'product_name' => 'required|max:255',
            'brand_id' => 'required',
            'unit_id' => 'required',
            'category_id' => 'required',
            'purchase_price' => 'required',
            'selling_price' => 'required',
            'initial_stock' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'data' => [
                    'error' => $validatedData->errors()->toArray()
                ]
            ], 422);
        }


        try {
            $this->storeData($request);
            return response()->json([
                'data' => [
                    'message' => 'Product saved successfully'
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

    public function edit(Request $request)
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
                'data' => [
                    'message' => 'Product Deleted Successfully'
                ]
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
            ]);

            $product = new Product();
            $product->product_name = $request->product_name;
            $product->company_name = $request->company_name;
            $product->size = $request->size;
            $product->color = $request->color;
            $product->unit_id = $request->unit_id;
            $product->save();

            return response()->json([
                'message' => 'Product information saved successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

    public function getAll()
    {
        try {
            $product = Product::select('product_name', 'id')->orderBy('id', 'desc')->where('store_id', 1)->get()->map->formatSelect();
            return response()->json(
                [
                    'data' => $product
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

    public function getAllRaw()
    {
        try {
            $product = Product::orderBy('id', 'desc')->where('store_id', 1)->get()->map->format();
            return response()->json(
                [
                    'data' => $product
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
