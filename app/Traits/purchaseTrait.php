<?php

namespace App\Traits;

use App\Account;
use App\ProductStock;
use App\Purchase;
use App\PurchaseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait PurchaseTrait
{
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

    public function purchase(Request $request)
    {
        DB::beginTransaction();
        try {
            $account = Account::find($request->account_id);
            if ($account->current_balance < $request->payment) {
                return [
                    'error' => true,
                    'message' => 'You dont have enough balance in the account.'
                ];
            }
            $purchase = new Purchase();
            $purchase->invoice_no = $request->invoice_no;
            $purchase->date = $request->date;
            $purchase->supplier_id = $request->supplier_id;
            $purchase->account_id = $request->account_id;
            $purchase->grand_total = $request->grand_total;
            $purchase->payment = $request->payment;
            $purchase->due = $request->due;
            $purchase->total = $request->total;
            $purchase->other_cost = $request->other_cost;
            $purchase->discount = $request->discount;
            $purchase->store_id = 1;
            $purchase->save();
            foreach ($request->items as $item) {
                $purchaseItem = new PurchaseDetail();
                $purchaseItem->purchase_id = $purchase->id;
                $purchaseItem->product_id = $item['product_id'];
                $purchaseItem->unit_price = $item['unit_price'];
                $purchaseItem->qty = $item['qty'];
                $purchaseItem->total = $item['total'];
                $purchaseItem->save();
                $productStock = new ProductStock();
                $productStock->product_id = $item['product_id'];
                $productStock->purchase_price = $item['unit_price'];
                $productStock->stock += $item['qty'];
                $productStock->selling_price = $request->selling_price;
                $this->addStock($productStock);
            }

            $account->current_balance -= $request->payment;
            $account->save();

            DB::commit();

            return [
                'error' => false,
                'message' => 'Purchase successfully done'
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
            return [
                'error' => true,
                'message' => $th->getMessage()
            ];
        }
    }
}
