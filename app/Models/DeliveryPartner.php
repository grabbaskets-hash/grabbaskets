<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class DeliveryPartner extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'delivery_partners';
    
    protected $guard = 'delivery_partner';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'alternate_phone',
        'address',
        'city',
        'state',
        'pincode',
        'date_of_birth',
        'gender',
        'vehicle_type',
        'vehicle_number',
        'license_number',
        'license_expiry',
        'vehicle_rc_number',
        'insurance_number',
        'insurance_expiry',
        'profile_photo',
        'license_photo',
        'vehicle_photo',
        'aadhar_number',
        'aadhar_photo',
        'pan_number',
        'pan_photo',
        'bank_account_holder',
        'bank_account_number',
        'bank_ifsc_code',
        'bank_name',
        'status',
        'is_verified',
        'is_online',
        'is_available',
        'current_latitude',
        'current_longitude',
        'location_updated_at',
        'current_address',
        'rating',
        'total_orders',
        'completed_orders',
        'cancelled_orders',
        'total_earnings',
        'this_month_earnings',
        'working_hours',
        'max_delivery_distance',
        'cash_on_delivery_enabled',
        'online_payment_enabled',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'admin_notes',
        'approved_at',
        'last_active_at',
        'registration_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'aadhar_number',
        'pan_number',
        'bank_account_number'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'license_expiry' => 'date',
        'insurance_expiry' => 'date',
        'is_verified' => 'boolean',
        'is_online' => 'boolean',
        'is_available' => 'boolean',
        'current_latitude' => 'decimal:8',
        'current_longitude' => 'decimal:8',
        'location_updated_at' => 'datetime',
        'rating' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'this_month_earnings' => 'decimal:2',
        'working_hours' => 'array',
        'cash_on_delivery_enabled' => 'boolean',
        'online_payment_enabled' => 'boolean',
        'approved_at' => 'datetime',
        'last_active_at' => 'datetime'
    ];

    /**
     * Get the orders assigned to this delivery partner.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'delivery_partner_id');
    }

    /**
     * Scope to get only approved partners.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get only available partners.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                    ->where('is_online', true)
                    ->where('status', 'approved');
    }

    /**
     * Scope to get partners in a specific city.
     */
    public function scopeInCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Check if partner is available for delivery.
     */
    public function isAvailableForDelivery(): bool
    {
        return $this->status === 'approved' && 
               $this->is_verified && 
               $this->is_online && 
               $this->is_available;
    }

    /**
     * Get partner's current location.
     */
    public function getCurrentLocation(): ?array
    {
        if ($this->current_latitude && $this->current_longitude) {
            return [
                'latitude' => (float) $this->current_latitude,
                'longitude' => (float) $this->current_longitude,
                'address' => $this->current_address,
                'updated_at' => $this->location_updated_at
            ];
        }
        return null;
    }

    /**
     * Update partner's location.
     */
    public function updateLocation(float $latitude, float $longitude, ?string $address = null): bool
    {
        return $this->update([
            'current_latitude' => $latitude,
            'current_longitude' => $longitude,
            'current_address' => $address,
            'location_updated_at' => now(),
            'last_active_at' => now()
        ]);
    }

    /**
     * Calculate distance from a given location.
     */
    public function distanceFrom(float $latitude, float $longitude): ?float
    {
        if (!$this->current_latitude || !$this->current_longitude) {
            return null;
        }

        $earthRadius = 6371; // Earth's radius in kilometers

        $latFrom = deg2rad((float) $this->current_latitude);
        $lonFrom = deg2rad((float) $this->current_longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Check if partner is within delivery radius of a location.
     */
    public function canDeliverTo(float $latitude, float $longitude): bool
    {
        $distance = $this->distanceFrom($latitude, $longitude);
        return $distance !== null && $distance <= $this->max_delivery_distance;
    }

    /**
     * Get partner's completion rate.
     */
    public function getCompletionRateAttribute(): float
    {
        if ($this->total_orders === 0) {
            return 100.0;
        }
        return round(($this->completed_orders / $this->total_orders) * 100, 2);
    }

    /**
     * Get partner's cancellation rate.
     */
    public function getCancellationRateAttribute(): float
    {
        if ($this->total_orders === 0) {
            return 0.0;
        }
        return round(($this->cancelled_orders / $this->total_orders) * 100, 2);
    }

    /**
     * Check if partner is working today.
     */
    public function isWorkingToday(): bool
    {
        if (!$this->working_hours) {
            return true; // If no hours set, assume always working
        }

        $today = strtolower(Carbon::now()->format('l')); // monday, tuesday, etc.
        $todayHours = $this->working_hours[$today] ?? null;

        if (!$todayHours || !isset($todayHours['start']) || !isset($todayHours['end'])) {
            return false;
        }

        $currentTime = Carbon::now()->format('H:i');
        return $currentTime >= $todayHours['start'] && $currentTime <= $todayHours['end'];
    }

    /**
     * Mark partner as online.
     */
    public function goOnline(): bool
    {
        return $this->update([
            'is_online' => true,
            'last_active_at' => now()
        ]);
    }

    /**
     * Mark partner as offline.
     */
    public function goOffline(): bool
    {
        return $this->update([
            'is_online' => false,
            'is_available' => false
        ]);
    }

    /**
     * Toggle availability.
     */
    public function toggleAvailability(): bool
    {
        return $this->update([
            'is_available' => !$this->is_available,
            'last_active_at' => now()
        ]);
    }

    /**
     * Get profile photo URL.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo) {
            if (str_starts_with($this->profile_photo, 'http')) {
                return $this->profile_photo;
            }
            return asset('storage/' . $this->profile_photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get document URL.
     */
    public function getDocumentUrl(string $documentField): ?string
    {
        $path = $this->{$documentField};
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }
        return asset('storage/' . $path);
    }

    /**
     * Update earnings after completing an order.
     */
    public function addEarnings(float $amount): bool
    {
        return $this->update([
            'total_earnings' => $this->total_earnings + $amount,
            'this_month_earnings' => $this->this_month_earnings + $amount
        ]);
    }

    /**
     * Reset monthly earnings (to be called at month start).
     */
    public function resetMonthlyEarnings(): bool
    {
        return $this->update(['this_month_earnings' => 0]);
    }

    /**
     * Get status badge HTML.
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending Review</span>',
            'approved' => '<span class="badge bg-success">Approved</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>',
            'suspended' => '<span class="badge bg-secondary">Suspended</span>',
            'inactive' => '<span class="badge bg-light text-dark">Inactive</span>'
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Get online status badge.
     */
    public function getOnlineStatusBadgeAttribute(): string
    {
        if (!$this->is_online) {
            return '<span class="badge bg-secondary">Offline</span>';
        }

        if ($this->is_available) {
            return '<span class="badge bg-success">Available</span>';
        }

        return '<span class="badge bg-warning">Busy</span>';
    }

    /**
     * Check if documents are complete.
     */
    public function hasCompleteDocuments(): bool
    {
        $requiredDocs = [
            'license_photo',
            'vehicle_photo',
            'aadhar_photo'
        ];

        foreach ($requiredDocs as $doc) {
            if (!$this->{$doc}) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get pending orders count.
     */
    public function getPendingOrdersCountAttribute(): int
    {
        return $this->orders()
                   ->whereIn('delivery_status', ['assigned', 'picked_up', 'in_transit'])
                   ->count();
    }

    /**
     * Get today's earnings.
     */
    public function getTodayEarningsAttribute(): float
    {
        return $this->orders()
                   ->where('delivery_status', 'delivered')
                   ->whereDate('delivered_at', today())
                   ->sum('delivery_fee') ?? 0;
    }
}
