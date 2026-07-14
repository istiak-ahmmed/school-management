<?php

namespace App\Models;

use App\Enums\SalaryPaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Expense extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'voucher_no',
        'expense_category_id',
        'amount',
        'payment_method_id',
        'paid_to',
        'expense_date',
        'approved_by',
        'entered_by',
        'note',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('receipts')
            ->useFallbackUrl('/images/placeholder.jpg')
            ->useFallbackPath(public_path('/images/placeholder.jpg'));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('optimized')
            ->format('webp')
            ->quality(80)
            ->nonQueued();
    }

    protected $casts = [
        'expense_date'   => 'date',
    ];

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function enterer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entered_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function scopeBetweenDates($query, $from, $to)
    {
        return $query->when($from, function ($q) use ($from) {
            $q->whereDate('expense_date', '>=', $from);
        })->when($to, function ($q) use ($to) {
            $q->whereDate('expense_date', '<=', $to);
        });
    }
}
