<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesGroupAccess;
use App\Http\Requests\StoreGroupRequest;
use App\Models\Group;
use App\Services\BalanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GroupController extends Controller
{
    use AuthorizesGroupAccess;

    public function index(Request $request): View
    {
        $groups = $request->user()
            ->groups()
            ->with('owner')
            ->withCount(['users', 'expenses'])
            ->latest('groups.created_at')
            ->get();

        return view('groups.index', ['groups' => $groups]);
    }

    public function create(): View
    {
        return view('groups.create');
    }

    public function store(StoreGroupRequest $request): RedirectResponse
    {
        $group = DB::transaction(function () use ($request): Group {
            $group = Group::create([
                'name' => $request->validated('name'),
                'owner_id' => $request->user()->id,
            ]);

            $group->users()->attach($request->user()->id);

            return $group;
        });

        return redirect()
            ->route('groups.show', $group)
            ->with('status', 'Group created.');
    }

    public function show(Group $group, BalanceService $balanceService): View
    {
        $this->authorizeGroupMember($group);

        $group->load([
            'owner',
            'users' => fn ($query) => $query->orderBy('name'),
            'expenses' => fn ($query) => $query->with('payer')->latest('expense_date')->latest(),
        ]);

        return view('groups.show', [
            'group' => $group,
            'balances' => $balanceService->calculateForGroup($group),
        ]);
    }
}
