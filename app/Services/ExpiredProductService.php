<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ExpiredProductService
{
    /**
     * Get all expired products
     */
    public function getExpiredProducts(): Collection
    {
        return Product::expired()
            ->with('category')
            ->orderBy('expired_date', 'asc')
            ->get();
    }

    /**
     * Get products that will expire within given days
     */
    public function getNearExpiryProducts(int $days = 30): Collection
    {
        return Product::nearExpiry($days)
            ->with('category')
            ->orderBy('expired_date', 'asc')
            ->get();
    }

    /**
     * Get expiry summary statistics
     */
    public function getExpirySummary(): array
    {
        $expiredCount = Product::expired()->count();
        $nearExpiryCount = Product::nearExpiry(30)->count();
        $nearExpiry7Days = Product::nearExpiry(7)->count();
        $totalWithDate = Product::whereNotNull('expired_date')->count();

        return [
            'expired' => $expiredCount,
            'near_expiry_30_days' => $nearExpiryCount,
            'near_expiry_7_days' => $nearExpiry7Days,
            'total_with_expiry_date' => $totalWithDate,
        ];
    }

    /**
     * Get expired products with pagination
     */
    public function getExpiredProductsPaginated(int $perPage = 15)
    {
        return Product::expired()
            ->with('category')
            ->orderBy('expired_date', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get near expiry products with pagination
     */
    public function getNearExpiryProductsPaginated(int $days = 30, int $perPage = 15)
    {
        return Product::nearExpiry($days)
            ->with('category')
            ->orderBy('expired_date', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get all products with expiry concerns (expired + near expiry)
     */
    public function getAllExpiryConcerns(int $nearExpiryDays = 30): Collection
    {
        return Product::where(function ($query) use ($nearExpiryDays) {
            $query->expired()
                ->orWhere(function ($q) use ($nearExpiryDays) {
                    $q->nearExpiry($nearExpiryDays);
                });
        })
            ->with('category')
            ->orderBy('expired_date', 'asc')
            ->get();
    }
}
