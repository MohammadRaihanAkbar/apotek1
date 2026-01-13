<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'qty',
        'reference_type',
        'reference_id',
        'note',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function recordMovement(
        int $productId,
        string $type,
        int $qty,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $note = null
    ): self {
        return self::create([
            'product_id' => $productId,
            'user_id' => auth()->id(),
            'type' => $type,
            'qty' => abs($qty),
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'note' => $note,
        ]);
    }
}
