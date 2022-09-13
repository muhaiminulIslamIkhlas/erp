<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Category;
use App\Traits\ResponseTrait;

class CategoryController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        try {
            $category = Category::orderBy('id', 'desc')->paginate($request->get('perPage'), ['category_name', 'id', 'description'], 'page');
            return $this->success($category);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function storeData(Request $request)
    {
        if ($request->id) {
            $category = Category::find($request->id);
        } else {
            $category = new Category();
        }
        $category->store_id = 1;
        $category->category_name = $request->category_name;
        $category->added_by = "Admin";
        $category->description = $request->description;
        $category->save();
    }

    public function create(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'category_name' => 'required|max:255',
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
            'category_name' => 'required|max:255',
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
            $category = Category::find($id);
            $category->delete();
            return $this->success(null, 'Deleted Successfully');
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getItem($id)
    {
        try {
            $category = Category::find($id);
            return $this->success($category);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getAll()
    {
        try {
            $category = Category::select('category_name', 'id')->orderBy('id', 'desc')->where('store_id', 1)->get()->map->formatSelect();
            return $this->success($category);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

}
