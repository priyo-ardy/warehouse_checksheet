<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait HasBlamable
{
    protected static function bootBlameable()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->created_at = Carbon::now();
                $model->updated_by = Auth::id();
                $model->updated_at = Carbon::now();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_at = Carbon::now();
                $model->updated_by = Auth::id();
            }
        });
    }
}
