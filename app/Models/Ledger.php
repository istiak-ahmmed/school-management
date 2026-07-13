<?php

namespace App\Models;

use App\Enums\LedgerAccountType;
use App\Enums\LedgerEntryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ledger extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'account_head',
        'account_type',
        'amount',
        'entry_type',
        'reference_type',
        'reference_id',
        'description',
        'created_by',
    ];

    protected $casts = [
        'date'         => 'date',
        'account_type' => LedgerAccountType::class,
        'entry_type'   => LedgerEntryType::class,
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
