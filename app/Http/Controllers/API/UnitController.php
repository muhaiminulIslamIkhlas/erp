<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{

    use ResponseTrait;

    public function storeData(Request $request)
    {
        if ($request->id) {
            $unit = Unit::find($request->id);
        } else {
            $unit = new Unit();
        }
        $unit->store_id = 1;
        $unit->unit_name = $request->unit_name;
        $unit->save();
    }

    public function index(Request $request)
    {
        $unit = Unit::orderBy('id', 'desc')->paginate($request->get('perPage'), ['*'], 'page');
        return $this->success($unit);
    }

    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'unit_name' => 'required|max:255',
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

    public function getAll()
    {
        try {
            $unit = Unit::select('unit_name', 'id')->orderBy('id', 'desc')->where('store_id', 1)->get()->map->formatSelect();
            return $this->success($unit);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function getById($id)
    {
        try {
            $unit = Unit::find($id);
            return $this->success($unit);
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
            $unit = Unit::find($id);
            $unit->delete();
            return $this->success(null, 'Deleted Successfully');
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $unit = Unit::where('unit_name', 'like', '%' . $request->search . '%')->orderBy('id', 'desc')->paginate($request->get('perPage'), ['*'], 'page');
            return $this->success($unit);
        } catch (\Throwable $th) {
            return $this->failure($th->getMessage());
        }
    }
}
