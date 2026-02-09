<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialAsisstanceRecipient extends Model
{
    use HasUuids,SoftDeletes;
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

    public function socialAsisstance()
    {
        return $this->belongsTo(SocialAsisstance::class);
    }
    public function headOfFamily()
    {
        return $this->belongsTo(HeadOfFamily::class);
    }
}
