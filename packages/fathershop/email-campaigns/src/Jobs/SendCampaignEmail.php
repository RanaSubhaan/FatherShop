<?php

namespace FatherShop\EmailCampaigns\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use FatherShop\EmailCampaigns\Models\Campaign;
use FatherShop\EmailCampaigns\Models\Customer;
use FatherShop\EmailCampaigns\Mail\CampaignMail;
use FatherShop\EmailCampaigns\Repositories\CampaignRepository;

class SendCampaignEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The campaign instance.
     *
     * @var \FatherShop\EmailCampaigns\Models\Campaign
     */
    protected $campaign;

    /**
     * The customer instance.
     *
     * @var \FatherShop\EmailCampaigns\Models\Customer
     */
    protected $customer;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @param  \FatherShop\EmailCampaigns\Models\Campaign  $campaign
     * @param  \FatherShop\EmailCampaigns\Models\Customer  $customer
     * @return void
     */
    public function __construct(Campaign $campaign, Customer $customer)
    {
        $this->campaign = $campaign;
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     *
     * @param  \FatherShop\EmailCampaigns\Repositories\CampaignRepository  $campaignRepository
     * @return void
     */
    public function handle(CampaignRepository $campaignRepository)
    {
        try {
            // Send email
            Mail::to($this->customer->email)
                ->send(new CampaignMail($this->campaign, $this->customer));

            // Log success
            $campaignRepository->logEmailStatus(
                $this->campaign,
                $this->customer->id,
                'sent'
            );
        } catch (Exception $e) {
            // Log failure
            $campaignRepository->logEmailStatus(
                $this->campaign,
                $this->customer->id,
                'failed',
                $e->getMessage()
            );

            // Throw exception to retry job
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // Log permanent failure after max retries
        app(CampaignRepository::class)->logEmailStatus(
            $this->campaign,
            $this->customer->id,
            'failed',
            'Max retries exceeded: ' . $exception->getMessage()
        );
    }
}