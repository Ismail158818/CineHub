<?php
namespace  App\Http\Controllers\Fun_Services;

use App\Models\Type;

class Fun_Type
{
    public function add_type_services($validated)
    {
        $a = Type::where('name', $validated['name'])->get();
        if(!$a->isEmpty())
        {
            return 'false';
        }
        else{
            $type = Type::create([
                'name' => $validated['name']
            ]);
            return 'true';
        }
    }

}
