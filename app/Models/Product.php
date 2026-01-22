<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_obat',
        'name',
        'description',
        'category_id',
        'image',
        'indikasi_umum',
        'komposisi',
        'dosis',
        'efek_samping',
        'no_registrasi',
        'stock',
        'price',
        'is_active',
        'expired_date',
    ];

    protected $casts = [
        'expired_date' => 'datetime',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the stock movements for the product.
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get the sale items for the product.
     */
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Scope: Get expired products (expired_date < today)
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->whereNotNull('expired_date')
            ->where('expired_date', '<', Carbon::today());
    }

    /**
     * Scope: Get products near expiry (within X days)
     */
    public function scopeNearExpiry(Builder $query, int $days = 30): Builder
    {
        return $query->whereNotNull('expired_date')
            ->where('expired_date', '>=', Carbon::today())
            ->where('expired_date', '<=', Carbon::today()->addDays($days));
    }

    /**
     * Scope: Get products that are not expired
     */
    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('expired_date')
                ->orWhere('expired_date', '>=', Carbon::today());
        });
    }

    /**
     * Accessor: Check if product is expired
     */
    public function getIsExpiredAttribute(): bool
    {
        if (!$this->expired_date) {
            return false;
        }
        return Carbon::parse($this->expired_date)->lt(Carbon::today());
    }

    /**
     * Accessor: Get days until expiry (negative if already expired)
     */
    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->expired_date) {
            return null;
        }
        return Carbon::today()->diffInDays($this->expired_date, false);
    }

    /**
     * Accessor: Get expiry status label
     */
    public function getExpiryStatusAttribute(): string
    {
        if (!$this->expired_date) {
            return 'no_date';
        }

        $days = $this->days_until_expiry;

        if ($days < 0) {
            return 'expired';
        } elseif ($days <= 30) {
            return 'near_expiry';
        } else {
            return 'safe';
        }
    }
}

