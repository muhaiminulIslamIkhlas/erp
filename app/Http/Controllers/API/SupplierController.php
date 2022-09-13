<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Supplier;
use App\Traits\ResponseTrait;

class SupplierController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        try {
            $supplier = Supplier::orderBy('id', 'desc')->paginate($request->get('perPage'), ['supplier_name', 'supplier_phone', 'supplier_address', 'id'], 'page');
            return $this->success($supplier);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function storeData(Request $request)
    {
        if ($request->id) {
            $supplier = Supplier::find($request->id);
        } else {
            $supplier = new Supplier();
        }
        $supplier->store_id = 1;
        $supplier->supplier_name = $request->supplier_name;
        $supplier->supplier_phone = $request->supplier_phone;
        $supplier->supplier_address = $request->supplier_address;
        $supplier->save();
    }

    public function create(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'supplier_name' => 'required|max:255',
            'supplier_phone' => 'required',
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
            'supplier_name' => 'required|max:255',
            'supplier_phone' => 'required',
            'id' => 'required'
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
            $supplier = Supplier::find($id);
            $supplier->delete();
            return $this->success(null, 'Deleted Successfully');
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getItem($id)
    {
        try {
            $supplier = Supplier::find($id);
            return $this->success($supplier);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getAll()
    {
        try {
            $supplier = Supplier::select('supplier_name', 'supplier_phone', 'id')->orderBy('id', 'desc')->where('store_id', 1)->get()->map->formatSelect();
            return $this->success($supplier);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }
}
