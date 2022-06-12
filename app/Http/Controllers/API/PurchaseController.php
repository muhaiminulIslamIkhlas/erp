<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductStock;
use App\Models\PurchaseHistory;
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

            $available_product = DB::table('product_stocks')->where('product_id', $product_id)->where('buying_price', $buying_price)->first();
            
            if($available_product->id) {
               
                $stored_qty = $available_product->qty;
                //return response()->json($stored_qty);
                $updated_data = DB::table('product_stocks')->where('product_id', $product_id)->where('buying_price', $buying_price)->update(['qty', $stored_qty+$qty]);

               return response()->json($updated_data);
            }
            else {
                $productStock = new ProductStock();
               
                $productStock->qty = $qty;
                $productStock->buying_price = $buying_price;
                $productStock->selling_price = $request->selling_price;
                $productStock->product_id = $request->product_id;

                $productStock->save();
            }

            // $purchaseHistory = new PurchaseHistory();
            // $seller_name = DB::table('sellers')->where('seller_id', $seller_id)->first();
            // $product_name = DB::table('products')->where('product_id', $product_id)->first();

            // $purchaseHistory->seller_name = $seller_name;
            // $purchaseHistory->product_name = $product_name;
            // $purchaseHistory->qty = $qty;
            // $purchaseHistory->buying_price = $request->buying_price;
            // $purchaseHistory->commission = $commission;
            // $purchaseHistory->total_price = (( $qty * $buying_price) * $commission) / 100;
            // $purchaseHistory->purchase_time =  date('d-m-y h:i:s');

            // $purchaseHistory->save();

            return response()->json([
                'data' => ""
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }

    }
}
