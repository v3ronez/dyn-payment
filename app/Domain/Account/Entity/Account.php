<?php

declare(strict_types=1);

namespace App\Domain\Account;

use App\Domain\Account\Enums\AccountStatus;
use App\Domain\User\Entity\User;
use App\Domain\Wallet\Entity\Wallet;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class Account extends Model
{
    use HasFactory;
    use HasApiTokens;
    use SoftDeletes;
    use HasUuids;

    protected $fillable = [
        'user_id',
        'number',
        'balance',
        'status',
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
            'status' => AccountStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wallet(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }
}
