<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'          => 'required|string|unique:products,name',
            'price'         => 'required|numeric',
            'category_id'   => 'required|exists:categories,id',
            'description'   => 'nullable',
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
