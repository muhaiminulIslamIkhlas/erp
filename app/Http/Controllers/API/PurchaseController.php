<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Purchase;
use App\PurchaseDetail;
use App\Traits\purchaseTrait;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    use purchaseTrait;

    public function index(Request $request)
    {
        $purchase = tap(Purchase::orderBy('id', 'desc')->paginate($request->get('perPage'), ['*'], 'page'), function ($data) {
            return $data->getCollection()->transform(function ($value) {
                return $value->formatList();
            });
        });

        return response()->json([
            'data' => $purchase
        ], 202);
    }

    public function makePurchase(Request $request)
    {
        try {
            $message = $this->purchase($request);
            if ($message['error']) {
                return response()->json([
                    'error' => $message['message']
                ], 500);
            }
            return response()->json(
                [
                    'data' => [
                        'message' => $message['message']
                    ]
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function getById($id)
    {
        try {
            $purchaseDetails = Purchase::where('id', $id)->with('details')->first()->formatDetails();
            return response()->json([
                'data' => $purchaseDetails
            ], 202);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
