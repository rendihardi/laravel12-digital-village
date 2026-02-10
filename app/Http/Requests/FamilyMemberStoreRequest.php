<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FamilyMemberStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
      public function rules(): array
    {
        return [
            'name'=> 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'head_of_family_id' => 'required|exists:head_of_families,id',
            'password' => 'required|string|min:8',
            'profile_picture'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'identity_number'=> 'required|string|unique:head_of_families',
            'gender'=> 'required|string|in:male,female',
            'date_of_birth'=> 'required|date',
            'phone_number'=> 'required|string',
            'occupation'=> 'required|string',
            'marital_status'=> 'required|string|in:single,married',
            'relation'=> 'required|string|in:wife,child,husbaand',
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
}
