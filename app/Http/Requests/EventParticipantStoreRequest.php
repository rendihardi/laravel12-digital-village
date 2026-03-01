<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EventParticipantStoreRequest extends FormRequest
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
            'event_id' => 'required|exists:events,id',
            'head_of_family_id' => 'required|exists:head_of_families,id',
            'quantity' => 'required|numeric',
            'payment_status' => 'nullable|string|in:paid,cancelled,unpaid',
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
