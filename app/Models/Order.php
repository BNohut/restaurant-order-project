<?php

namespace App\Models;

use App\Models\Traits\ProcessOrderItems;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory, HasUuids, ProcessOrderItems;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_uuid',
        'company_uuid',
        'address_uuid',
        'status_uuid',
        'total_amount',
        'delivery_fee',
        'tax_amount',
        'payment_method',
        'payment_status',
        'notes',
        'delivery_time',
        'courier_uuid',
        'items_snapshot',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'float',
        'delivery_fee' => 'float',
        'tax_amount' => 'float',
        'delivery_time' => 'datetime',
        'items_snapshot' => 'array',
    ];

    /**
     * Get the customer who placed the order.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    /**
     * Get the company that received the order.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_uuid', 'uuid');
    }

    /**
     * Get the address for delivery.
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_uuid', 'uuid');
    }

    /**
     * Get the courier assigned to the order.
     */
    public function courier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'courier_uuid', 'uuid');
    }

    /**
     * Get the status of this order.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_uuid', 'uuid');
    }

    /**
     * Get the order items for this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_uuid', 'uuid');
    }
}
