<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{

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

        return response()->json([
            'data' => $unit
        ], 202);
    }

    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'unit_name' => 'required|max:255',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'data' => [
                    'error' => $validatedData->errors()->toArray()
                ]
            ], 422);
        }


        try {
            $this->storeData($request);
            return response()->json([
                'data' => [
                    'message' => 'Unit saved successfully'
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

    public function getById($id)
    {
        try {
            $unit = Unit::find($id);

            return response()->json([
                'data' => $unit
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
                'message' => 'Unit Updated Successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

    public function delete($id)
    {
        try {
            $unit = Unit::find($id);
            $unit->delete();

            return response()->json([
                'data' => [
                    'message' => 'Unit Deleted Successfully'
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $unit = Unit::where('unit_name', 'like', '%' . $request->search . '%')->orderBy('id', 'desc')->paginate($request->get('perPage'), ['*'], 'page');
            return response()->json([
                'data' => $unit
            ], 202);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
