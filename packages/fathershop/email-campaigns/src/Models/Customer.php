<?php

namespace FatherShop\EmailCampaigns\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone_number',
        'status',
        'plan_expiry_date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'plan_expiry_date' => 'date',
    ];

    /**
     * Get all campaign logs for this customer.
     */
    public function campaignLogs()
    {
        return $this->hasMany(CampaignLog::class);
    }

    /**
     * Scope a query to only include customers with a specific status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include customers whose plan expires within the given days.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpiringWithin($query, $days)
    {
        $today = now();
        $futureDate = now()->addDays($days);
        
        return $query->whereBetween('plan_expiry_date', [$today, $futureDate]);
    }
}