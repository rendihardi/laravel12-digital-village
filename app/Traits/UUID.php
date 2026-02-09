<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UUID
{
    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootUUID()
    {
       parent::boot();
       static::creating(function ($model) {
           if ($model->getKey() === null) {
                // Generate a UUID for the primary key
                $model->setAttribute($model->getKeyName(), Str::uuid()->toString());
           } 
       });
    }

    public function getIncrementing()
    {
        return false;
    }   

    public function getKeyType()
    {
        return 'string';
    }
}