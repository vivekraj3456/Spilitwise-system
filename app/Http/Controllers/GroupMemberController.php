<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesGroupAccess;
use App\Http\Requests\StoreGroupMemberRequest;
use App\Models\Expense;
use App\Models\ExpenseSplit;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class GroupMemberController extends Controller
{
    use AuthorizesGroupAccess;

    public function store(StoreGroupMemberRequest $request, Group $group): RedirectResponse
    {
        $this->authorizeGroupOwner($group);

        $user = User::where('email', $request->validated('email'))->firstOrFail();
        $group->users()->syncWithoutDetaching([$user->id]);

        return redirect()
            ->route('groups.show', $group)
            ->with('status', $user->name.' was added to the group.');
    }

    public function destroy(Group $group, User $user): RedirectResponse
    {
        $this->authorizeGroupOwner($group);

        if ($user->id === $group->owner_id) {
            return back()->withErrors(['member' => 'The group owner cannot be removed.']);
        }

        $hasExpenseHistory = Expense::query()
            ->where('group_id', $group->id)
            ->where('paid_by_user_id', $user->id)
            ->exists()
            || ExpenseSplit::query()
                ->where('user_id', $user->id)
                ->whereHas('expense', fn ($query) => $query->where('group_id', $group->id))
                ->exists();

        if ($hasExpenseHistory) {
            return back()->withErrors([
                'member' => 'This member has expense history in the group and cannot be removed.',
            ]);
        }

        $group->users()->detach($user->id);

        return redirect()
            ->route('groups.show', $group)
            ->with('status', $user->name.' was removed from the group.');
    }
}
