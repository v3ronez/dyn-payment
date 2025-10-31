<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Contracts\Actions\Action;
use App\Domain\User\Entity\User;
use App\Domain\User\Enums\UserStatus;
use Illuminate\Support\Facades\Auth;

class Login implements Action
{
    private mixed $success;

    private array $error;

    public function __construct(
        private string $email,
        private string $password,
    ) {
        $this->success = null;
        $this->error = [];
    }

    public function execute(): self
    {
        //TODO: Check if user are not pending

        /** @var User $user */
        $user = User::where('email', $this->email)->first();
        if (! $user) {
            $this->error[] = 'User not found';

            return $this;
        }
        if ($user->status === UserStatus::Disapprove) {
            $this->error[] = 'Users with status disapprove cannot login';

            return $this;
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->error = ['Invalid email or password'];

            return $this;
        }

        $this->success = ['access_token' => $user->createToken('access_token')->plainTextToken];

        return $this;
    }

    public function getSuccess(): mixed
    {
        return $this->success;
    }

    public function getError(): array
    {
        return $this->error;
    }

    public function hasError(): bool
    {
        return count($this->error) > 0;
    }
}
