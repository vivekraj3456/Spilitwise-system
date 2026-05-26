<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesGroupAccess;
use App\Http\Requests\StoreExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseSplit;
use App\Models\Group;
use App\Services\EqualSplitCalculator;
use App\Services\Money;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    use AuthorizesGroupAccess;

    public function index(Request $request): View
    {
        $expenses = Expense::query()
            ->whereHas('group.users', fn ($query) => $query->whereKey($request->user()->id))
            ->with(['group', 'payer'])
            ->latest('expense_date')
            ->latest()
            ->paginate(15);

        return view('expenses.index', ['expenses' => $expenses]);
    }

    public function create(Group $group): View
    {
        $this->authorizeGroupMember($group);

        return view('expenses.create', [
            'group' => $group,
            'members' => $group->users()->orderBy('name')->get(),
        ]);
    }

    public function store(
        StoreExpenseRequest $request,
        Group $group,
        EqualSplitCalculator $splitCalculator
    ): RedirectResponse {
        $this->authorizeGroupMember($group);

        $validated = $request->validated();
        $amountCents = Money::parseToCents($validated['amount']);

        /** @var UploadedFile|null $receiptImage */
        $receiptImage = $request->file('receipt_image');

        if ($amountCents < 1) {
            throw ValidationException::withMessages(['amount' => 'Expense amount must be greater than zero.']);
        }

        $members = $group->users()->orderBy('id')->get();

        if (! $members->contains('id', (int) $validated['paid_by_user_id'])) {
            throw ValidationException::withMessages(['paid_by_user_id' => 'The payer must belong to this group.']);
        }

        $expense = DB::transaction(function () use ($group, $validated, $amountCents, $members, $splitCalculator): Expense {
            $expense = Expense::create([
                'group_id' => $group->id,
                'paid_by_user_id' => $validated['paid_by_user_id'],
                'title' => $validated['title'],
                'category' => $validated['category'] ?? 'General',
                'amount_cents' => $amountCents,
                'expense_date' => $validated['expense_date'],
            ]);

            foreach ($splitCalculator->split($amountCents, $members) as $userId => $shareCents) {
                ExpenseSplit::create([
                    'expense_id' => $expense->id,
                    'user_id' => $userId,
                    'amount_cents' => $shareCents,
                ]);
            }

            return $expense;
        });

        if ($receiptImage !== null) {
            $path = $receiptImage->store('receipts', 'public');
            $expense->forceFill(['receipt_image' => $path])->save();
        }

        return redirect()
            ->route('groups.expenses.show', [$group, $expense])
            ->with('status', 'Expense added.');
    }

    public function show(Group $group, Expense $expense): View
    {
        $this->authorizeGroupMember($group);
        abort_unless($expense->group_id === $group->id, 404);

        $expense->load(['payer', 'splits.user']);

        return view('expenses.show', [
            'group' => $group,
            'expense' => $expense,
        ]);
    }
}
