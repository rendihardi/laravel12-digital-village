<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name'=> 'required|string|max:255',
            'password' => 'nullable|string|min:8',
        ];
    }

    public function attributes()
    {
        return[
            'name'=> 'Name',
            'password' => 'Password',
        ];
    }

    public function messages()
    {
        return [
            'required'=>'harus diisi',
            'string'=>"atribut harus berupa string",
            'max'=>'atribut makasimal :max karakter',
            'min'=>'atribut minimal :min karakter',
            'unique' => 'atribut harus unique',
        ];
    }
}
