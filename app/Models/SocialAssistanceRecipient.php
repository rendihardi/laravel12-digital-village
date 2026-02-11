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

    public function scopeSearch($query, $search){
        return $query->whereHas('socialAsisstance', function ($query) use ($search) {
            $query->where('name', 'like', '%'.$search.'%')
            ->orWhere('provider', 'like', '%'.$search.'%');
        })->orWhereHas('headOfFamily', function ($query) use ($search) {
            $query->where('name', 'like', '%'.$search.'%')
            ->orWhere('nik', 'like', '%'.$search.'%')
            ->orWhere('phone', 'like', '%'.$search.'%');
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
