<?php

namespace FatherShop\EmailCampaigns\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'campaign_id',
        'customer_id',
        'status',
        'error_message',
        'sent_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * Get the campaign that owns the log.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the customer that received the email.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Status constants
     */
    const STATUS_QUEUED = 'queued';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';

    /**
     * Get all valid statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_QUEUED,
            self::STATUS_SENT,
            self::STATUS_FAILED,
        ];
    }
}