<?php

namespace App\Http\Requests;

use App\Models\HeadOfFamily;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log as Log;

class HeadOfFamilyUpdateRequest extends FormRequest
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

        $headId = $this->route('head_of_family');
        
        $headOfFamily = HeadOfFamily::find($headId);
        $userId = $headOfFamily ? $headOfFamily->user_id : null;

        return [
            'name'=> 'required|string|max:255',
            'email' => "nullable|string|email|max:255|unique:users,email,{$userId}",
            'password' => 'nullable|string|min:8',
            'profile_picture'=> 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'identity_number'=> "required|string|unique:head_of_families,identity_number,{$headId}",
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
}
