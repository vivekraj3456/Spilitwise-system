<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:50'],
            'amount' => ['required', 'regex:/^(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d{1,2})?$/'],
            'paid_by_user_id' => ['required', 'integer', 'exists:users,id'],
            'expense_date' => ['required', 'date'],
            'receipt_image' => ['nullable', 'image', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'amount.regex' => 'Enter an amount with up to two decimal places (commas optional).',
        ];
    }
}
