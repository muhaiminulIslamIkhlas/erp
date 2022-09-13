<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductStock;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $product = tap(Product::orderBy('id', 'desc')->paginate($request->get('perPage'), ['*'], 'page'), function ($data) {
            return $data->getCollection()->transform(function ($value) {
                return $value->format();
            });
        });

        return $this->success($product);
    }

    public function getItem($id)
    {
        $product = Product::find($id);
        return $this->success($product);
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
        if (!$request->id) {
            $productStock = new ProductStock();
            $productStock->product_id = $product->id;
            $productStock->purchase_price = $request->purchase_price;
            $productStock->stock = $request->initial_stock;
            $productStock->selling_price = $request->selling_price;
            $this->addStock($productStock);
        }
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
            return $this->failure($validatedData->errors()->toArray());
        }

        try {
            $this->storeData($request);
            return $this->success(null, 'Inserted successfully');
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getProductById($id)
    {
        try {
            $product = Product::find($id);
            return $this->success($product);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function edit(Request $request)
    {
        try {
            $this->storeData($request);
            return $this->success(null, 'Updated Successfully');
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::find($id);
            $product->delete();
            return $this->success(null, 'Deleted Successfully');
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function productSave(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'product_name' => 'required',
                'company_name' => 'required',
                'size' => 'required',
                'color' => 'required',
                'unit_id' => 'required',
            ]);

            if ($validatedData->fails()) {
                return $this->failure($validatedData->errors()->toArray());
            }

            $product = new Product();
            $product->product_name = $request->product_name;
            $product->company_name = $request->company_name;
            $product->size = $request->size;
            $product->color = $request->color;
            $product->unit_id = $request->unit_id;
            $product->save();

            return $this->success(null, 'Inserted successfully');
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getAll()
    {
        try {
            $product = Product::select('product_name', 'id')->orderBy('id', 'desc')->where('store_id', 1)->get()->map->formatSelect();
            return $this->success($product);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getAllRaw()
    {
        try {
            $product = Product::orderBy('id', 'desc')->where('store_id', 1)->get()->map->format();
            return $this->success($product);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }
}
