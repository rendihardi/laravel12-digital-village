<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SocialAssistanceRecipientStoreRequest extends FormRequest
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
            'social_assistance_id' => 'required|exists:social_assistances,id',
            'head_of_family_id' => 'required|exists:head_of_families,id',
            'amount' => 'required|numeric',
            'bank' => 'required|string',
            'account_number' => 'required|string',
            'proof' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
            'reason' => 'required|string',
            'status'=>'nullable|string',
        ];
    }

    public function attributes()
    {
        return parent::attributes();
    }

       public function prepareForValidation()
{
    $user = Auth::user();

    if ($user->hasRole('head-of-family')) {
        $this->merge([
            'head_of_family_id' => $user->headOfFamily->id
        ]);
    }
}
}
