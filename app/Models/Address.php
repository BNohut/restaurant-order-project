<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_uuid',
        'company_uuid',
        'address_line1',
        'address_line2',
        'city',
        'district',
        'postal_code',
        'country',
        'is_default',
        'title'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the address.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    /**
     * Get the company that owns the address.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_uuid', 'uuid');
    }

    /**
     * Check if the address belongs to a company.
     */
    public function isCompanyAddress(): bool
    {
        return $this->company_uuid !== null;
    }

    /**
     * Check if the address belongs to a user.
     */
    public function isUserAddress(): bool
    {
        return $this->user_uuid !== null;
    }
}
