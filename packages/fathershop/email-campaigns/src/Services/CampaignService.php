<?php

namespace FatherShop\EmailCampaigns\Services;

use FatherShop\EmailCampaigns\Contracts\CampaignRepositoryInterface;
use FatherShop\EmailCampaigns\Contracts\CustomerRepositoryInterface;
use FatherShop\EmailCampaigns\Jobs\SendCampaignEmail;
use FatherShop\EmailCampaigns\Models\Campaign;
use FatherShop\EmailCampaigns\Models\CampaignLog;
use Illuminate\Support\Facades\Bus;

class CampaignService
{
    /**
     * @var CampaignRepositoryInterface
     */
    protected $campaignRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * CampaignService constructor.
     *
     * @param CampaignRepositoryInterface $campaignRepository
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CampaignRepositoryInterface $campaignRepository,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->campaignRepository = $campaignRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Create a new campaign.
     *
     * @param array $data
     * @return \FatherShop\EmailCampaigns\Models\Campaign
     */
    public function createCampaign(array $data)
    {
        // Set default values
        $data['is_html'] = $data['is_html'] ?? true;
        $data['status'] = Campaign::STATUS_DRAFT;

        return $this->campaignRepository->create($data);
    }

    /**
     * Update an existing campaign.
     *
     * @param int $id
     * @param array $data
     * @return \FatherShop\EmailCampaigns\Models\Campaign
     */
    public function updateCampaign($id, array $data)
    {
        return $this->campaignRepository->update($id, $data);
    }

    /**
     * Send a campaign to the filtered audience.
     *
     * @param int $campaignId
     * @return array Status of the operation
     */
    public function sendCampaign($campaignId)
    {
        $campaign = $this->campaignRepository->find($campaignId);
        
        if (!$campaign) {
            throw new \Exception("Campaign not found");
        }

        // Can't send a campaign that's already in process
        if (in_array($campaign->status, [Campaign::STATUS_QUEUED, Campaign::STATUS_SENDING])) {
            throw new \Exception("Campaign is already in queue or being sent");
        }

        // Filter customers based on campaign settings
        $criteria = [];
        
        if ($campaign->filter_status) {
            $criteria['status'] = $campaign->filter_status;
        }
        
        if ($campaign->filter_expiry_days) {
            $criteria['expiry_days'] = $campaign->filter_expiry_days;
        }
        
        $customers = empty($criteria) 
            ? $this->customerRepository->all() 
            : $this->customerRepository->filterByCriteria($criteria);
        
        if ($customers->isEmpty()) {
            throw new \Exception("No customers match the filter criteria");
        }
        
        // Update campaign status
        $campaign->update(['status' => Campaign::STATUS_QUEUED]);
        
        // Create logs for all customers
        $logs = [];
        $jobs = [];
        $batchSize = config('email-campaigns.batch_size', 100);
        $batch = [];
        
        foreach ($customers as $customer) {
            // Create a log entry
            $log = $this->campaignRepository->logEmail(
                $campaign->id,
                $customer->id,
                CampaignLog::STATUS_QUEUED
            );
            
            $logs[] = $log->id;
            
            // Create a job
            $job = new SendCampaignEmail($campaign, $customer, $log);
            
            // Add to batch or dispatch immediately
            if ($batchSize > 1) {
                $batch[] = $job;
                
                // Dispatch batch when it reaches the size limit
                if (count($batch) >= $batchSize) {
                    Bus::batch($batch)
                        ->onQueue(config('email-campaigns.queue.queue', 'emails'))
                        ->dispatch();
                    
                    $jobs[] = count($batch);
                    $batch = [];
                }
            } else {
                $job->onQueue(config('email-campaigns.queue.queue', 'emails'))->dispatch();
                $jobs[] = 1;
            }
        }
        
        // Dispatch any remaining jobs in the batch
        if (!empty($batch)) {
            Bus::batch($batch)
                ->onQueue(config('email-campaigns.queue.queue', 'emails'))
                ->dispatch();
            
            $jobs[] = count($batch);
        }
        
        // Update campaign status to sending
        $campaign->update(['status' => Campaign::STATUS_SENDING]);
        
        return [
            'status' => 'success',
            'message' => 'Campaign queued for sending',
            'customers_count' => $customers->count(),
            'jobs_dispatched' => count($jobs),
        ];
    }

    /**
     * Get campaign statistics.
     *
     * @param int $campaignId
     * @return array
     */
    public function getCampaignStats($campaignId)
    {
        $campaign = $this->campaignRepository->find($campaignId);
        
        if (!$campaign) {
            throw new \Exception("Campaign not found");
        }
        
        return [
            'id' => $campaign->id,
            'title' => $campaign->title,
            'status' => $campaign->status,
            'sent_count' => $campaign->sent_count,
            'failed_count' => $campaign->failed_count,
            'total_logs' => $campaign->logs()->count(),
        ];
    }
}