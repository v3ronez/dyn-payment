<?php

declare(strict_types=1);

namespace App\Domain\Transfer;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Transfer extends Model
{
    use HasFactory;
    use HasApiTokens;
    use SoftDeletes;
    use HasUuids;

    protected $fillable = [];
}
