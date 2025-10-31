<?php

declare(strict_types=1);

namespace App\Domain\User\FormRequests;

use App\Domain\User\Enums\DocumentType;
use App\Domain\User\Enums\UserType;
use App\Domain\User\Validations\Rules\DocumentIDRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'first_name' => ['required', 'max:100'],
            'last_name' => ['required', 'max:100'],
            'document_id' => ['required'],
            'email' => ['required', 'unique:users', 'email'],
            'document_id' => ['required', new DocumentIDRule()],
            'document_type' => ['required', Rule::enum(DocumentType::class)],
            'type' => ['required', Rule::enum(UserType::class)],
            'password' => ['required', 'confirmed', 'min:8', 'max:60'],
        ];
    }
}
