<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Brand;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        try {
            $brand = Brand::orderBy('id', 'desc')->paginate($request->get('perPage'), ['brand_name', 'description', 'id'], 'page');
            return $this->success($brand);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getAll()
    {
        try {
            $brand = Brand::select('brand_name', 'id')->orderBy('id', 'desc')->where('store_id', 1)->get()->map->formatSelect();
            return $this->success($brand);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function storeData(Request $request)
    {
        if ($request->id) {
            $brand = Brand::find($request->id);
        } else {
            $brand = new Brand();
        }
        $brand->store_id = 1;
        $brand->brand_name = $request->brand_name;
        $brand->added_by = "Admin";
        $brand->description = $request->description;
        $brand->save();
    }

    public function create(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'brand_name' => 'required|max:255'
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
            'brand_name' => 'required|max:255',
            'added_by' => 'required',
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
            $brand = Brand::find($id);
            $brand->delete();
            return $this->success(null, 'Deleted Successfully');
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getItem($id)
    {
        try {
            $brand = Brand::find($id);
            return $this->success($brand);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }
}
