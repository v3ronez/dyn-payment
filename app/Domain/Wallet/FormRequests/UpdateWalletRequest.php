<?php

declare(strict_types=1);

namespace App\Domain\Wallet\FormRequests;

use App\Domain\Wallet\Enums\WalletStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWalletRequest extends FormRequest
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
            'name' => ['string', 'max:100'],
            'balance' => ['integer', 'min:0'],
            'status' => [Rule::enum(WalletStatus::class)],
        ];
    }
}
