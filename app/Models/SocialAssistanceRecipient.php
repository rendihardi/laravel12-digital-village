<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialAssistanceRecipient extends Model
{
    use HasUuids,SoftDeletes,HasFactory;
    protected $fillable = [
        'social_asisstance_id',
        'head_of_family_id',
        'amount',
        'reason',
        'bank',
        'account_number',
        'proof',
        'status',
    ];

public function scopeSearch($query, $search)
{
    return $query->where(function ($q) use ($search) {

        $q->whereHas('socialAssistance', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('provider', 'like', "%{$search}%");
        })

        ->orWhereHas('headOfFamily.user', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        })

        ->orWhereHas('headOfFamily', function ($q) use ($search) {
            $q->where('identity_number', 'like', "%{$search}%")
              ->orWhere('phone_number', 'like', "%{$search}%");
        });

    });
}


    public function socialAssistance()
    {
        return $this->belongsTo(SocialAssistance::class);
    }
    public function headOfFamily()
    {
        return $this->belongsTo(HeadOfFamily::class);
    }
}
