<?php

namespace LaravelEnso\Members\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Members\app\Models\Member;
use LaravelEnso\Select\app\Traits\OptionsBuilder;

class MemberSelectController extends Controller
{
    use OptionsBuilder;

    protected $model = Member::class;
}
