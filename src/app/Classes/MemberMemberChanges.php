<?php

namespace LaravelEnso\Members\app\Classes;

use LaravelEnso\Core\app\Models\User;
use LaravelEnso\Members\app\Models\Member;

class MemberMemberChanges
{
    private $member;
    private $synced;
    private $message = null;

    public function __construct(Member $member, array $synced)
    {
        $this->member = $member;
        $this->synced = $synced;
    }

    public function log()
    {
        if (count($this->synced['attached'])) {
            $this->computeAttached();
        }

        if (count($this->synced['detached'])) {
            $this->computeDetached();
        }

        if ($this->message) {
            $this->member->logEvent($this->message, 'users');
        }
    }

    private function computeAttached()
    {
        $attached = $this->users($this->synced['attached']);

        $this->message = 'added the user(s): '.$attached;
    }

    private function computeDetached()
    {
        $detached = $this->users($this->synced['detached']);

        if ($this->message) {
            $this->message .= ' and ';
        }

        $this->message .= 'removed the user(s): '.$detached;
    }

    private function users($ids)
    {
        return User::whereIn('id', $ids)
            ->get()
            ->pluck('name')
            ->implode(', ');
    }
}
