<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Development extends Model
{
    use HasUuids, SoftDeletes;
    protected $fillable = [
        'thumbnail',
        'name',
        'description',
        'person_in_charge',
        'start_date',
        'end_date',
        'amount',
        'status',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }

    public function developmentApplicants()
    {
         return $this->hasMany(DevelopmentApplicant::class)
                ->orderBy('created_at', 'desc');
    }


}
