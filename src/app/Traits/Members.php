<?php

namespace LaravelEnso\Members\app\Traits;

use LaravelEnso\Members\app\Models\Member;

trait Members
{
    public function members()
    {
        return $this->belongsToMany(Member::class);
    }
}
