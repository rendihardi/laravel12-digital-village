<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileImage extends Model
{
    use HasUuids, SoftDeletes;
    protected $fillable = [
        'profile_id',
        'image',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
