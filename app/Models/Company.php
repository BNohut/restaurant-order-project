<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory, HasUuids;

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
        'name',
        'description',
        'logo_path',
        'phone',
        'email',
        'website',
        'tax_number',
        'owner_uuid',
        'address_uuid',
        'business_hours',
        'delivery_radius',
        'delivery_fee',
        'minimum_order',
        'is_active',
        'is_featured',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'business_hours' => 'array',
        'delivery_radius' => 'float',
        'delivery_fee' => 'float',
        'minimum_order' => 'float',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the owner (user) of the company.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_uuid', 'uuid');
    }

    /**
     * Get the address of the company.
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_uuid', 'uuid');
    }

    /**
     * Get the products for the company.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'company_uuid', 'uuid');
    }

    /**
     * Get the orders for the company.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'company_uuid', 'uuid');
    }
}
