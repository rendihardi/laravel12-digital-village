<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'thumbnail' => asset('storage/' . $this->thumbnail),
            'name' => $this->name,
            'about' => $this->about,
            'headman' => $this->headman,
            'people' => $this->people,
            'location' => $this->location,
            'agricultural_area' =>(float)(String) $this->agricultural_area,
            'total_area' => (float)(String) $this->total_area,
            'images' => ProfileImagesresource::collection($this->profileImages)

        ];
    }
}
