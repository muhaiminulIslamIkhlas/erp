<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\PurchaseHistory;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function purchase(Request $request)
    {
        try {
            $product_id = $request->product_id;
            $seller_id = $request->seller_id;
            $qty = $request->qty;
            $buying_price = $request->buying_price;
            $commission = $request->commission;

            $available_product = ProductStock::where('product_id', $product_id)->where('buying_price', $buying_price)->first();

            if ($available_product) {

                $available_product->qty = $available_product->qty + $qty;
                $available_product->save();
            } else {
                $productStock = new ProductStock();

                $productStock->qty = $qty;
                $productStock->buying_price = $buying_price;
                $productStock->selling_price = $request->selling_price;
                $productStock->product_id = $request->product_id;

                $productStock->save();
            }

            $purchaseHistory = new PurchaseHistory();
            $seller_name = Seller::where('id', $seller_id)->pluck('seller_name')->first();
            $product_name = Product::where('id', $seller_id)->pluck('product_name')->first();

            $purchaseHistory->seller_name = $seller_name;
            $purchaseHistory->product_name = $product_name;
            $purchaseHistory->qty = $qty;
            $purchaseHistory->buying_price = $request->buying_price;
            $purchaseHistory->commission = $commission;
            $t = $qty * $buying_price;
            $purchaseHistory->total_price = $t - ($t * $commission) / 100;
            $purchaseHistory->purchase_time =  date('Y-m-d');

            $purchaseHistory->save();

            return response()->json([
                'message' =>  'Purchase Completed'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }
}
