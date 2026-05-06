<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class EqualSplitCalculator
{
    /**
     * @param  iterable<User>  $users
     * @return array<int, int>
     */
    public function split(int $amountCents, iterable $users): array
    {
        if ($amountCents < 1) {
            throw new InvalidArgumentException('Amount must be at least one cent.');
        }

        $members = $users instanceof Collection ? $users : collect($users);
        $members = $members->sortBy('id')->values();

        if ($members->isEmpty()) {
            throw new InvalidArgumentException('At least one group member is required.');
        }

        $baseShare = intdiv($amountCents, $members->count());
        $remainder = $amountCents % $members->count();

        return $members
            ->mapWithKeys(function (User $user, int $index) use ($baseShare, $remainder): array {
                return [$user->id => $baseShare + ($index < $remainder ? 1 : 0)];
            })
            ->all();
    }
}
