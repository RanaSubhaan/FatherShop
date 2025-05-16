<?php

namespace FatherShop\EmailCampaigns\Contracts;

interface CampaignRepositoryInterface
{
    /**
     * Get all campaigns.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Get campaign by ID.
     *
     * @param int $id
     * @return \FatherShop\EmailCampaigns\Models\Campaign|null
     */
    public function find($id);

    /**
     * Create a new campaign.
     *
     * @param array $data
     * @return \FatherShop\EmailCampaigns\Models\Campaign
     */
    public function create(array $data);

    /**
     * Update a campaign.
     *
     * @param int $id
     * @param array $data
     * @return \FatherShop\EmailCampaigns\Models\Campaign
     */
    public function update($id, array $data);

    /**
     * Delete a campaign.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id);

    /**
     * Log an email sent for a campaign.
     *
     * @param int $campaignId
     * @param int $customerId
     * @param string $status
     * @param string|null $errorMessage
     * @return \FatherShop\EmailCampaigns\Models\CampaignLog
     */
    public function logEmail($campaignId, $customerId, $status, $errorMessage = null);

    /**
     * Update campaign stats (sent and failed counts).
     *
     * @param int $campaignId
     * @return \FatherShop\EmailCampaigns\Models\Campaign
     */
    public function updateStats($campaignId);
}