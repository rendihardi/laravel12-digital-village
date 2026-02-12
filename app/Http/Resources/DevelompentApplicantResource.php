<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DevelompentApplicantResource extends JsonResource
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
            'head_of_family'=> new HeadOfFamilyResource($this->headOfFamily),
            'development'=> new DevelopmentResurce($this->development),
            'status'=>$this->status
        ];
    }
}
