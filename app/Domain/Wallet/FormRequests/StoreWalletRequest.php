<?php

declare(strict_types=1);

namespace App\Domain\Wallet\FormRequests;

use App\Domain\Wallet\Enums\WalletStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWalletRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'balance' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::enum(WalletStatus::class)],
        ];
    }
}
