<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function storeData(Request $request)
    {
        if($request->id)
        {
            $seller = Seller::find($request->id);
        }
        else {
            $seller = new Seller();
        }

        $seller->seller_name = $request->seller_name;
        $seller->seller_phone = $request->seller_phone;
        $seller->seller_company = $request->seller_company;

        $seller->save();
    }

    public function index()
    {
        try {
            $seller = Seller::all();

            return response()->json([
                'data' => $seller
            ], 202);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

    public function getById($id)
    {
        try {
            $seller = Seller::find($id);

            return response()->json([
                'data' => $seller
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
                'message' => 'Seller Updated Successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

    public function store(Request $request)
    {
        try {

            $this->storeData($request);

            return response()->json([
                'message' => 'Seller Saved Successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $seller = Seller::find($request->id);
            $seller->delete();

            return response()->json([
                'message' => 'Seller Deleted Successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }
}
