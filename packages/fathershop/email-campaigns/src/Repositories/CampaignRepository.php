<?php

namespace FatherShop\EmailCampaigns\Repositories;

use FatherShop\EmailCampaigns\Contracts\CampaignRepositoryInterface;
use FatherShop\EmailCampaigns\Models\Campaign;
use FatherShop\EmailCampaigns\Models\CampaignLog;

class CampaignRepository implements CampaignRepositoryInterface
{
    /**
     * @var Campaign
     */
    protected $model;

    /**
     * @var CampaignLog
     */
    protected $logModel;

    /**
     * CampaignRepository constructor.
     *
     * @param Campaign $campaign
     * @param CampaignLog $campaignLog
     */
    public function __construct(Campaign $campaign, CampaignLog $campaignLog)
    {
        $this->model = $campaign;
        $this->logModel = $campaignLog;
    }

    /**
     * Get all campaigns.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Get campaign by ID.
     *
     * @param int $id
     * @return \FatherShop\EmailCampaigns\Models\Campaign|null
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Create a new campaign.
     *
     * @param array $data
     * @return \FatherShop\EmailCampaigns\Models\Campaign
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update a campaign.
     *
     * @param int $id
     * @param array $data
     * @return \FatherShop\EmailCampaigns\Models\Campaign
     */
    public function update($id, array $data)
    {
        $campaign = $this->find($id);
        
        if (!$campaign) {
            throw new \Exception("Campaign not found");
        }

        $campaign->update($data);
        return $campaign;
    }

    /**
     * Delete a campaign.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $campaign = $this->find($id);
        
        if (!$campaign) {
            return false;
        }

        return $campaign->delete();
    }

    /**
     * Log an email sent for a campaign.
     *
     * @param int $campaignId
     * @param int $customerId
     * @param string $status
     * @param string|null $errorMessage
     * @return \FatherShop\EmailCampaigns\Models\CampaignLog
     */
    public function logEmail($campaignId, $customerId, $status, $errorMessage = null)
    {
        $log = $this->logModel->create([
            'campaign_id' => $campaignId,
            'customer_id' => $customerId,
            'status' => $status,
            'error_message' => $errorMessage,
            'sent_at' => $status === CampaignLog::STATUS_SENT ? now() : null,
        ]);

        // Update the campaign stats after logging
        $this->updateStats($campaignId);

        return $log;
    }

    /**
     * Update campaign stats (sent and failed counts).
     *
     * @param int $campaignId
     * @return \FatherShop\EmailCampaigns\Models\Campaign
     */
    public function updateStats($campaignId)
    {
        $campaign = $this->find($campaignId);
        
        if (!$campaign) {
            throw new \Exception("Campaign not found");
        }

        // Count sent and failed emails
        $sentCount = $this->logModel
            ->where('campaign_id', $campaignId)
            ->where('status', CampaignLog::STATUS_SENT)
            ->count();

        $failedCount = $this->logModel
            ->where('campaign_id', $campaignId)
            ->where('status', CampaignLog::STATUS_FAILED)
            ->count();

        // Update campaign
        $campaign->update([
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
        ]);

        // Update overall status if all emails have been processed
        $totalProcessed = $sentCount + $failedCount;
        $totalQueued = $this->logModel
            ->where('campaign_id', $campaignId)
            ->count();

        if ($totalProcessed >= $totalQueued && $totalQueued > 0) {
            $status = ($failedCount === 0) ? Campaign::STATUS_SENT : Campaign::STATUS_FAILED;
            $campaign->update(['status' => $status]);
        }

        return $campaign;
    }
}