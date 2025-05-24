<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|string',
            'link' => 'required|url',
            'cast' => 'required|array',
            'rating' => 'required|numeric|min:1|max:10',
            'type_id' => 'required|array|exists:types,id',
            'image' => 'sometimes|file|image|max:2048'
        ];
          
    }

}
