<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesGroupAccess;
use App\Http\Requests\StoreGroupMemberRequest;
use App\Models\Expense;
use App\Models\ExpenseSplit;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class GroupMemberController extends Controller
{
    use AuthorizesGroupAccess;

    public function store(StoreGroupMemberRequest $request, Group $group): RedirectResponse
    {
        $this->authorizeGroupOwner($group);

        $rawInput = (string) $request->validated('member');
        $input = Str::of($rawInput)->squish()->toString();

        if ($input === '') {
            return back()->withErrors(['member' => 'Enter a member name or email.']);
        }

        $user = $this->resolveMemberToUser($group, $input);
        $changes = $group->users()->syncWithoutDetaching([$user->id]);
        $wasAdded = ! empty($changes['attached']);

        $status = $wasAdded
            ? $user->name.' was added to the group.'
            : $user->name.' is already in the group.';

        return redirect()
            ->route('groups.show', $group)
            ->with('status', $status);
    }

    private function resolveMemberToUser(Group $group, string $input): User
    {
        $isEmail = filter_var($input, FILTER_VALIDATE_EMAIL) !== false;

        if ($isEmail) {
            $existingUser = User::query()
                ->where('email', $input)
                ->where('is_guest', false)
                ->first();

            if ($existingUser) {
                return $existingUser;
            }

            $guessedName = Str::of($input)
                ->before('@')
                ->replace(['.', '_', '-'], ' ')
                ->squish();

            $name = $guessedName->isEmpty() ? 'Guest' : $guessedName->title()->toString();

            return $this->findOrCreateGuestForGroup($group, $name);
        }

        return $this->findOrCreateGuestForGroup($group, $input);
    }

    private function findOrCreateGuestForGroup(Group $group, string $name): User
    {
        $normalized = mb_strtolower(Str::of($name)->squish()->toString());

        $existing = $group->users()
            ->where('is_guest', true)
            ->whereRaw('lower(name) = ?', [$normalized])
            ->first();

        if ($existing) {
            return $existing;
        }

        return User::create([
            'name' => Str::of($name)->squish()->toString(),
            'email' => 'guest+'.Str::uuid().'@guest.local',
            'password' => Str::random(32),
            'is_guest' => true,
        ]);
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
