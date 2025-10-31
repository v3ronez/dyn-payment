<?php

declare(strict_types=1);

namespace App\Domain\Account\Entity;

use App\Domain\Account\ValueObjects\Status\StatusCast;
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
            'status' => StatusCast::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }
}
