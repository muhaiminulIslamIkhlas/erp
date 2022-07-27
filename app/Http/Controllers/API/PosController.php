<?php

namespace App\Http\Controllers\API;

use App\Account;
use App\Customer;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductStock;
use App\Sell;
use App\SellItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function sell(Request $request)
    {
        DB::beginTransaction();
        try {
            $rollbacDb = false;
            $customer = Customer::find($request->payment['customer_id']);
            $account = Account::find($request->payment['paymentMethod']);
            $sell = new Sell();
            $sell->sell_number = uniqid();
            $sell->customer_id = $request->payment['customer_id'];
            $sell->customer_phone = $customer->customer_phone;
            $sell->customer_name = $customer->customer_name;
            $sell->discount = $request->finalCalculation['discount'];
            $sell->other_cost = $request->finalCalculation['otherCost'];
            $sell->subtotal = $request->finalCalculation['subTotal'];
            $sell->due = $request->payment['due'];
            $sell->payment = $request->payment['payment'];
            $sell->qty = $request->finalCalculation['items'];
            $sell->total = $request->finalCalculation['total'];
            $sell->date = $request->finalCalculation['date'];
            $sell->account_id = $request->payment['paymentMethod'];
            $sell->save();

            //Increment Account
            $account->current_balance += $request->total;
            $account->save();

            //Sells Item
            $stockOutProduct = [];
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $productStock = ProductStock::where('product_id', $item['product_id'])->sum('stock');
                if ($item['qty'] > $productStock) {
                    $rollbacDb = true;
                    $stockOutProduct[] = [
                        'product_name' => $product->product_name,
                        'qty' => $productStock
                    ];
                    continue;
                }

                $totalBuyingPrice = 0;
                $qty = $item['qty'];
                $products = ProductStock::where('product_id', $item['product_id'])->get();
                foreach ($products as $item) {
                    if (!$qty) {
                        break;
                    }
                    $prevStock = $item->stock;
                    if ($item->stock <= $qty) {
                        $item->stock -=  $qty;
                        $totalBuyingPrice += $item->purchase_price * $qty;
                        $item->save();
                        $qty -= $prevStock;
                        $item->save();
                    } else {
                        $item->stock -= $qty;
                        $totalBuyingPrice += $item->purchase_price * $qty;
                        $item->save();
                        $qty = 0;
                    }
                }

                $sellsItem = new SellItems();
                $sellsItem->sell_id = $sell->id;
                $sellsItem->product_id = $item['product_id'];
                $sellsItem->product_name = $product->product_name;
                $sellsItem->qty = $item['qty'];
                $sellsItem->discount = $item['discount'];
                $sellsItem->price = $item['price'];
                $sellsItem->total_selling_price = $item['total'];
                $sellsItem->total_buying_price = $totalBuyingPrice;
                $product->save();
            }

            if ($rollbacDb) {
                DB::rollback();
                return response()->json([
                    'data' => [
                        'error' => true,
                        'stockedOutProduct' => $stockOutProduct
                    ]
                ], 200);
            } else {
                $account->current_balance += $request->payment['payment'];
                $account->save();
            }

            DB::commit();
            return response()->json([
                'data' => [
                    'message' => 'Sell completed Successfully',
                    'invoice_no' => $sell->sell_number,
                    'stockedOutProduct' => $stockOutProduct,
                    'error' => false,
                ]
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
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
