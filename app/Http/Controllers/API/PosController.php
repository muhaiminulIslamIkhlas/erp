<?php

namespace App\Http\Controllers\API;

use App\Account;
use App\Customer;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductStock;
use App\Sell;
use App\SellItems;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    use ResponseTrait;

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

            /**Increment Account*/
            $account->current_balance += $request->total;
            $account->save();

            /**Sells Item*/
            $stockOutProduct = [];
            $productCart = [];
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id'])->posFormate();
                $productCart[$item['product_id']] = $product;
                $productStock = ProductStock::where('product_id', $item['product_id'])->sum('stock');
                if ($item['qty'] > $productStock) {
                    $rollbacDb = true;
                    $stockOutProduct[] = [
                        'product_name' => $product['product_name'],
                        'qty' => $productStock
                    ];
                    continue;
                }

                $totalBuyingPrice = 0;
                $qty = $item['qty'];
                $productStocks = ProductStock::where('product_id', $item['product_id'])->get();
                foreach ($productStocks as $productStock) {
                    if (!$qty) {
                        break;
                    }
                    $prevStock = $productStock->stock;
                    if ($productStock->stock <= $qty) {
                        $productStock->stock =  0;
                        $totalBuyingPrice += $productStock->purchase_price * $qty;
                        $productStock->save();
                        $qty -= $prevStock;
                        $productStock->save();

                    } else {
                        $productStock->stock -= $qty;
                        $totalBuyingPrice += $productStock->purchase_price * $qty;
                        $productStock->save();
                        $qty = 0;

                    }
                }

                $sellsItem = new SellItems();
                $sellsItem->sell_id = $sell->id;
                $sellsItem->product_id = $item['product_id'];
                $sellsItem->product_name = $product['product_name'];
                $sellsItem->qty = $item['qty'];
                $sellsItem->discount = $item['discount'];
                $sellsItem->price = $item['price'];
                $sellsItem->total_selling_price = $item['total'];
                $sellsItem->total_buying_price = $totalBuyingPrice;
                $sellsItem->save();
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
                    'cart_item' => $productCart,
                    'customer' => $customer,
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
