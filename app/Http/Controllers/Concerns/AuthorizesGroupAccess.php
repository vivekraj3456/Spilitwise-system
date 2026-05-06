<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Group;

trait AuthorizesGroupAccess
{
    protected function authorizeGroupMember(Group $group): void
    {
        abort_unless(
            $group->users()->whereKey(auth()->id())->exists(),
            403
        );
    }

    protected function authorizeGroupOwner(Group $group): void
    {
        abort_unless($group->owner_id === auth()->id(), 403);
    }
}
