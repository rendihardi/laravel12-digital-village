<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventUpdateRequest extends FormRequest
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
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|integer',
            'date' => 'required|date',
            'time'=> 'required|date_format:H:i',
            'is_active' => 'required|boolean',
        ];
    }
    
    public function attributes()
    {
        return parent::attributes();
    }
}
