<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialAssistanceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
     public function rules(): array
    {
        return [
            'name'=> 'required|string|max:255',
            'thumbnail'=> 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
            'category'=> 'required|string|max:255',
            'description'=> 'required|string|max:255',
            'amount'=> 'required|integer',
            'provider'=> 'required|string|max:255',
            'is_available'=> 'required|boolean',
        ];
    }

    public function attributes()
    {
        return[
            'name'=> 'Name',
            'thumbnail'=> 'Thumbnail',
            'category'=> 'Category',
            'description'=> 'Description',
            'amount'=> 'Amount',
            'provider'=> 'Provider',
            'is_available'=> 'Is Available',
        ];
    }
}
