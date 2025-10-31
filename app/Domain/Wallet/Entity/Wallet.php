
<?php

declare(strict_types=1);

namespace App\Domain\Wallet\Entity;

use App\Domain\Account\Account;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class Wallet extends Model
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use SoftDeletes;
    use HasUuids;

    protected $fillable = [
        'name',
        'balance',
        'type',
        'status',
        'account_id',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (! array_key_exists($this->getKeyName(), $attributes)) {
            $this->setAttribute($this->getKeyName(), (string) Str::uuid7());
        }
    }

    public function account(): HasOne
    {
        return $this->hasOne(Account::class);
    }
}
