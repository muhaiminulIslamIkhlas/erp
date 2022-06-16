<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{

    public function storeData(Request $request)
    {
        if($request->id)
        {
            $unit = Unit::find($request->id);
        }
        else {
            $unit = new Unit();
        }
        $unit->unit_name = $request->unit_name;
        $unit->save();
    }

    public function index()
    {
        $unit = Unit::all();

        return response()->json([
            'data' => $unit
        ], 202);
    }

    public function store(Request $request)
    {
       try {

        $this->storeData($request);

        return response()->json([
            'message' => 'Unit saved successfully'
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
                'message' => 'Unit Deleted Successfully'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
    }

}
