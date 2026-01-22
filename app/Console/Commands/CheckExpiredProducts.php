<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiredProducts extends Command
{
    protected $signature = 'products:check-expired 
                            {--days=30 : Number of days to check for near expiry}
                            {--notify : Send notification for expired products}';

    protected $description = 'Check for expired and near-expiry products';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        $this->info('Checking expired products...');
        $this->newLine();

        // Get expired products
        $expiredProducts = Product::expired()->with('category')->get();

        if ($expiredProducts->isNotEmpty()) {
            $this->error("Found {$expiredProducts->count()} EXPIRED products:");
            $this->table(
                ['ID', 'Kode', 'Nama', 'Kategori', 'Expired Date'],
                $expiredProducts->map(fn($p) => [
                    $p->id,
                    $p->kode_obat,
                    $p->name,
                    $p->category?->name ?? '-',
                    $p->expired_date instanceof \Carbon\Carbon ? $p->expired_date->format('Y-m-d') : ($p->expired_date ?? '-'),
                ])
            );

            // Log expired products
            Log::warning('Expired products found', [
                'count' => $expiredProducts->count(),
                'products' => $expiredProducts->pluck('kode_obat')->toArray(),
            ]);
        } else {
            $this->info('✓ No expired products found.');
        }

        $this->newLine();

        // Get near expiry products
        $nearExpiryProducts = Product::nearExpiry($days)->with('category')->get();

        if ($nearExpiryProducts->isNotEmpty()) {
            $this->warn("Found {$nearExpiryProducts->count()} products expiring in the next {$days} days:");
            $this->table(
                ['ID', 'Kode', 'Nama', 'Kategori', 'Expired Date', 'Days Left'],
                $nearExpiryProducts->map(fn($p) => [
                    $p->id,
                    $p->kode_obat,
                    $p->name,
                    $p->category?->name ?? '-',
                    $p->expired_date instanceof \Carbon\Carbon ? $p->expired_date->format('Y-m-d') : ($p->expired_date ?? '-'),
                    $p->days_until_expiry,
                ])
            );

            // Log near expiry products
            Log::info('Near expiry products found', [
                'count' => $nearExpiryProducts->count(),
                'days' => $days,
                'products' => $nearExpiryProducts->pluck('kode_obat')->toArray(),
            ]);
        } else {
            $this->info("✓ No products expiring in the next {$days} days.");
        }

        $this->newLine();

        // Summary
        $this->info('Summary:');
        $this->line("  - Expired: {$expiredProducts->count()}");
        $this->line("  - Near Expiry ({$days} days): {$nearExpiryProducts->count()}");

        return Command::SUCCESS;
    }
}
