<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Customer;
use App\Sell;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    use ResponseTrait;

    public function storeData(Request $request)
    {
        if ($request->id) {
            $customer = Customer::find($request->id);
        } else {
            $customer = new Customer();
        }

        $customer->customer_name = $request->customer_name;
        $customer->customer_phone = $request->customer_phone;
        $customer->customer_address = $request->customer_address;
        $customer->added_by = "Admin";
        $customer->store_id = 1;

        $customer->save();
    }

    public function index(Request $request)
    {
        try {
            $customer = Customer::orderBy('id', 'desc')->paginate($request->get('perPage'), ['customer_name', 'customer_phone', 'customer_address', 'id'], 'page');
            return $this->success($customer);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getById($id)
    {
        try {
            $customer = Customer::find($id);
            return $this->success($customer);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->storeData($request);
            return $this->success(null, 'Inserted successfully');
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function update(Request $request)
    {
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
            $customer = Customer::find($id);
            $customer->delete();
            return $this->success(null, 'Deleted Successfully');
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getAll()
    {
        try {
            $brand = Customer::select('customer_name', 'customer_phone', 'id')->orderBy('id', 'desc')->where('store_id', 1)->get()->map->formatSelect();
            return $this->success($brand);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getPreviousDue($id)
    {
        try {
            $previousDue = Sell::where('customer_id', $id)->sum('due');
            return $this->success($previousDue);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }
}
