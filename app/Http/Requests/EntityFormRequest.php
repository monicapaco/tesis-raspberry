<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntityFormRequest extends FormRequest
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
            //
            'name'=>'required|max:100',
            'type_document'=>'required|max:20',
            'n_document'=>'required|max:15',
            'address'=>'required',
            'region'=>'required',
            'province'=>'required',
            'district'=>'required',
            'phone'=>'max:15',
            'email'=>'max:50',
        ];
    }
}
