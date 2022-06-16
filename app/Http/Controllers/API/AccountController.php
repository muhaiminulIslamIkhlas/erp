<?php

namespace App\Http\Controllers\API;

use App\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function index()
    {
        try {
            $account = Account::all();
            return response()->json(
                [
                    'data' => $account
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function getItem($id)
    {
        try {
            $account = Account::find($id);
            return response()->json(
                [
                    'data' => $account
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function storeData(Request $request)
    {
        if ($request->id) {
            $account = Account::find($request->id);
        } else {
            $account = new Account();
        }
        $account->account_name = $request->account_name;
        $account->account_description = $request->account_description;
        $account->current_balance = $request->current_balance;
        $account->store_id = 1;
        $account->save();
    }

    public function create(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'account_name' => 'required|max:255',
            'current_balance' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'data' => [
                    'error' => $validatedData->errors()
                ]
            ], 400);
        }

        try {
            $this->storeData($request);
            return response()->json(
                [
                    'data' => [
                        'message' => 'inserted successfully'
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

    public function edit(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'account_name' => 'required|max:255',
            'current_balance' => 'required',
            'id' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'data' => [
                    'error' => $validatedData->errors()
                ]
            ], 400);
        }

        try {
            $this->storeData($request);
            return response()->json(
                [
                    'data' => [
                        'message' => 'updated successfully'
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

    public function delete($id)
    {
        try {
            $account = Account::find($id);
            $account->delete();
            return response()->json(
                [
                    'data' => [
                        'message' => 'deleted successfully'
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
}
