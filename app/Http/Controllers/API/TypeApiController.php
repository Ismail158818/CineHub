<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Fun_Services\Fun_Type;
use App\Http\Requests\TypeRequest;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeApiController extends Controller
{
    public function add_type(TypeRequest $request)
    {

        $validated = $request->validated();
        $create = new Fun_Type();

        $data = $create->add_type_services($validated);
        if ($data === 'true') {
            return response()->json(['status' => 'success', 'message' => 'Type added successfully']);
        }
        return response()->json(['status' => 'fail', 'message' => 'this type exist already']);
    }

}
