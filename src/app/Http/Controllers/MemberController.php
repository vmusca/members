<?php

namespace LaravelEnso\Members\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Members\app\Models\Member;
use LaravelEnso\Members\app\Http\Resources\Member as Resource;
use LaravelEnso\Members\app\Http\Requests\ValidateMemberRequest;

class MemberController extends Controller
{
    public function index()
    {
        error_log("Contrller members.index");
        return Resource::collection(
            Member::with('users')
                ->latest()
                ->get()
        );
    }

    public function store(ValidateMemberRequest $request)
    {
        $member = Member::store($request->validated());

        return [
            'message' => __('The member was successfully saved'),
            'member' => new Resource($member),
        ];
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return [
            'message' => __('The member was successfully deleted'),
        ];
    }
}
