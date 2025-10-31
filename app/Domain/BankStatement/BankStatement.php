<?php

declare(strict_types=1);

namespace App\Domain\BankStatement;

use App\Domain\Wallet\Entity\Wallet;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class BankStatement extends Model
{
    use HasFactory;
    use HasApiTokens;
    use SoftDeletes;
    use HasUuids;

    protected $fillable = [
        'waller_id',
        'amount',
        'account_origin',
        'account_destination',
        'current_balance',
        'transfer_date',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
