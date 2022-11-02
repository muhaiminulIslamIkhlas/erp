<?php

namespace App\Http\Controllers\API;

use App\Account;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use App\Transection;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        try {
            $account = Account::orderBy('id', 'desc')->paginate($request->get('perPage'), ['*'], 'page');
            return $this->success($account);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getItem($id)
    {
        try {
            $account = Account::find($id);
            return $this->success($account);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
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
            return $this->failure($validatedData->errors()->toArray());
        }

        try {
            $this->storeData($request);
            return $this->success(null, 'Inserted successfully');
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
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
            return $this->failure($validatedData->errors()->toArray());
        }

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
            $account = Account::find($id);
            $account->delete();
            return $this->success(null, 'Deleted Successfully');
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getAll()
    {
        try {
            $account = Account::select('*')->orderBy('id', 'desc')->where('store_id', 1)->get()->map->formatSelect();
            return $this->success($account);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getTotalBalance()
    {
        $TotalBalance = Account::where('store_id', 1)->sum('current_balance');
        return $this->success($TotalBalance);
    }

    public function addtransactionHistory($accountId, $amount, $type, $reason, $date)
    {
        try {
            $transection = new Transection();
            $transection->account_id = $accountId;
            $transection->type = $type;
            $transection->reason = $reason;
            $transection->amount = $amount;
            $transection->date = $date;
            $transection->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addAmount(Request $request)
    {
        DB::beginTransaction();
        try {
            $account = Account::find($request->account_id);
            $account->current_balance = $account->current_balance + $request->amount;
            $account->save();
            $this->addtransactionHistory($request->account_id, $request->amount, config('accountType.add_amount'), $request->reason, date('Y-m-d'));
            DB::commit();
            return $this->success($account, 'Amount Added successfully');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->failure($th->getMessage(), "Please try agian", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function widthdrawAmount(Request $request)
    {
        DB::beginTransaction();
        try {
            $account = Account::find($request->account_id);
            if ($request->amount > $account->current_balance) {
                return $this->failure(null, "You dont have enough amount", Response::HTTP_BAD_REQUEST);
            }
            $account->current_balance -= $request->amount;
            $account->save();
            $this->addtransactionHistory($request->account_id, $request->amount, config('accountType.widthdraw_amount'), $request->reason, date('Y-m-d'));
            DB::commit();
            return $this->success(null, 'Amount Added successfully');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->failure($th->getMessage());
        }
    }

    public function getAllTransection(Request $request)
    {
        try {
            $transection = Transection::orderBy('id', 'desc')
                ->join('accounts', 'accounts.id', 'transections.account_id')
                ->select('accounts.account_name', 'transections.*')
                ->paginate($request->get('perPage'), ['*'], 'page');

            return $this->success($transection);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }
}
