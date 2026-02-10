<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HeadOfFamilyRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'profile_picture'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'identity_number'=> 'required|string|unique:head_of_families',
            'gender'=> 'required|string|in:male,female',
            'date_of_birth'=> 'required|date',
            'phone_number'=> 'required|string',
            'occupation'=> 'required|string',
            'marital_status'=> 'required|string|in:single,married',
        ];
    }

    public function attributes()
    {
        return [
            'name'=> 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'profile_picture'=> 'Profile Picture',
            'identity_number'=> 'Identity Number',
            'gender'=> 'Gender',
            'date_of_birth'=> 'Date of Birth',
            'phone_number'=> 'Phone Number',
            'occupation'=> 'Occupation',
            'marital_status'=> 'Marital Status',
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'required'=>':attribute harus diisi',
    //         'string'=>":attribute harus berupa string",
    //         'max'=>':attribute makasimal :max karakter',
    //         'min'=>':attribute minimal :min karakter',
    //         'unique' => ':attribute harus unique',
    //         'image' => ':attribute harus berupa gambar',
    //         'email' => ':attribute harus berupa email',
    //         'in' => ':attribute harus memilih salah satu',
    //     ];
    // }
}
