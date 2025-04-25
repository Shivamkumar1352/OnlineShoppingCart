<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'discount'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    protected $appends = [
        'total',
        'discounted_price'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * Calculate item total (price after discount × quantity)
     */
    public function getTotalAttribute(): float
    {
        return ($this->price - $this->discount) * $this->quantity;
    }

    /**
     * Calculate discounted price per unit
     */
    public function getDiscountedPriceAttribute(): float
    {
        return $this->price - $this->discount;
    }

    /**
     * Get the product name with fallback for deleted products
     */
    public function getProductNameAttribute(): string
    {
        return $this->product?->name ?? '[Deleted Product]';
    }

    /**
     * Get the product image with fallback
     */
    public function getProductImageAttribute(): ?string
    {
        return $this->product?->image;
    }
}
