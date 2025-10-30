<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Domain\User\Enums\UserStatus;
use App\Domain\User\Enums\UserType;
use App\Domain\User\ValueObject\Document\DocumentIDCast;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use SoftDeletes;
    use HasUuids;

    protected $fillable = [
        'name',
        'email',
        'document_id',
        'document_type',
        'status',
        'password',
        'email_verified_at',
        'type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (! array_key_exists($this->getKeyName(), $attributes)) {
            $this->setAttribute($this->getKeyName(), (string) Str::uuid7());
        }
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'document_id' => DocumentIDCast::class,
            'type' => UserType::class,
            'status' => UserStatus::class,
        ];
    }
}
