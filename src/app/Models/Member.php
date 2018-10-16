<?php

namespace LaravelEnso\Members\app\Models;

use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\ActivityLog\app\Traits\LogsActivity;
use LaravelEnso\Members\app\Classes\MemberMemberChanges;

class Member extends Model
{
    use LogsActivity;

    protected $fillable = ['name'];

    protected $loggableLabel = 'name';

    protected $loggable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public static function store($attributes)
    {
        $member = null;

        \DB::transaction(function () use (&$member, $attributes) {
            $member = self::updateOrCreate(
                ['id' => $attributes['id'] ?? null],
                ['name' => $attributes['name']]
            );

            $synced = $member->users()->sync($attributes['userIds']);

            if (count($synced['attached']) && count($synced['detached'])) {
                (new MemberMemberChanges($member, $synced))
                    ->log();
            }
        });

        return $member;
    }

    public function delete()
    {
        try {
            parent::delete();
        } catch (\Exception $e) {
            throw new ConflictHttpException(__(
                'The member has activity in the system and cannot be deleted'
            ));
        }

        return ['message' => 'The member was successfully deleted'];
    }

    public function userIds()
    {
        return $this->users->pluck('id');
    }

    public function userList()
    {
        return $this->users->map(function ($user) {
            return [
                'name' => $user->person->name,
                'avatar' => $user->avatar,
            ];
        });
    }
}
