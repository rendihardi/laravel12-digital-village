<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialAsisstance extends Model
{
    use SoftDeletes,HasUlids;
    protected $fillable = [
        'name',
        'thumbnail',
        'category',
        'amount',
        'provider',
        'description',
        'is_available',
    ];

    public function socialAsisstanceRecipients()
    {
        return $this->hasMany(SocialAsisstanceRecipient::class);
    }
    
}
