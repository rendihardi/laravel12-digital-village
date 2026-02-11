<?php

namespace App\Http\Requests;

use App\Models\FamilyMember;
use Illuminate\Foundation\Http\FormRequest;

class FamilyMemberUpdateRequest extends FormRequest
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

        $memberId = $this->route('head_of_family');
        
        $familyMember = FamilyMember::find($memberId);
        $userId = $familyMember ? $familyMember->user_id : null;

        return [
            'name'=> 'required|string|max:255',
            'email' => "nullable|string|email|max:255|unique:users,email,{$userId}",
            'password' => 'nullable|string|min:8',
            'profile_picture'=> 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'identity_number'=> "required|string|unique:head_of_families,identity_number,{$memberId}",
            'gender'=> 'required|string|in:male,female',
            'date_of_birth'=> 'required|date',
            'phone_number'=> 'required|string',
            'occupation'=> 'required|string',
            'marital_status'=> 'required|string|in:single,married',
            'relation'=> 'required|string|in:wife,child,husband',
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
