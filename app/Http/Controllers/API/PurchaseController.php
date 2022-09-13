<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Purchase;
use App\PurchaseDetail;
use App\Traits\purchaseTrait;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    use purchaseTrait;
    use ResponseTrait;

    public function index(Request $request)
    {
        $purchase = tap(Purchase::orderBy('id', 'desc')->paginate($request->get('perPage'), ['*'], 'page'), function ($data) {
            return $data->getCollection()->transform(function ($value) {
                return $value->formatList();
            });
        });

        return $this->success($purchase);
    }

    public function makePurchase(Request $request)
    {
        try {
            $message = $this->purchase($request);
            if ($message['error']) {
                return $this->failure($message['message']);
            }
            return $this->success(null, $message['message']);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getById($id)
    {
        try {
            $purchaseDetails = Purchase::where('id', $id)->with('details')->first()->formatDetails();
            return $this->success($purchaseDetails);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }
}
