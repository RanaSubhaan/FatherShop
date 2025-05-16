<?php

namespace FatherShop\EmailCampaigns\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'subject',
        'body',
        'is_html',
        'filter_status',
        'filter_expiry_days',
        'sent_count',
        'failed_count',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_html' => 'boolean',
        'filter_expiry_days' => 'integer',
        'sent_count' => 'integer',
        'failed_count' => 'integer',
    ];

    /**
     * Get all logs for this campaign.
     */
    public function logs()
    {
        return $this->hasMany(CampaignLog::class);
    }

    /**
     * Campaign statuses
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_QUEUED = 'queued';
    const STATUS_SENDING = 'sending';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';

    /**
     * Get all valid statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_QUEUED,
            self::STATUS_SENDING,
            self::STATUS_SENT,
            self::STATUS_FAILED,
        ];
    }
}