<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemFormRequest extends FormRequest
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
            'category_id'=>'required',
            'codevar'=>'required|max:50',
            'name'=>'required|max:50',
            'stock'=>'required|numeric',
            'description'=>'max:512',
            'img'=>'mimes:jpeg,bmp,png'
            //
        ];
    }
}
