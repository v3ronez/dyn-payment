<?php

declare(strict_types=1);

namespace App\Domain\User\FormRequests;

use App\Domain\User\Enums\DocumentType;
use App\Domain\User\Enums\UserStatus;
use App\Domain\User\Enums\UserType;
use App\Domain\User\Validations\Rules\DocumentIDRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'first_name' => ['max:100'],
            'last_name' => ['max:100'],
            'document_id' => ['required'],
            'email' => ['unique:users', 'email'],
            'document_id' => [new DocumentIDRule()],
            'document_type' => ['integer:strict', Rule::enum(DocumentType::class)],
            'type' => ['integer:strict', Rule::enum(UserType::class)],
            'status' => ['integer:strict', Rule::enum(UserStatus::class)],
            'password' => ['confirmed', 'min:8', 'max:60'],
        ];
    }

    public function messages(): array
    {
        return [
            'document_id.required' => 'The document id is required.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email is invalid.',
            'document_id.document_id' => 'The document id is invalid.',
            'document_type.required' => 'The document type is required.',
            'type.required' => 'The type is required.',
            'status.required' => 'The status is required.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.max' => 'The password must be at most 60 characters.',
            'integer' => 'The :attribute must be an integer.',
        ];
    }
}
