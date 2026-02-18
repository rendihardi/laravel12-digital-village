<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialAssistanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,
            'name' => $this->name,
            'thumbnail' => asset('storage/' . $this->thumbnail),
            'category' => $this->category,
            'description' => $this->description,
            'amount' => $this->amount,
            'provider' => $this->provider,
            'is_available' => $this->is_available,
            'social_assistance_recipients' =>
            SocialAssistanceRecipientResource::collection(
        $this->whenLoaded('socialAssistanceRecipients')
        ),

        ];
    }
}
