<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendCampaignEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Campaign $campaign, public Customer $customer)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            Mail::to($this->customer->email)->send(new CampaignMailable($this->campaign));
            $status = 'sent';
        } catch (\Exception $e) {
            $status = 'failed';
        }

        CampaignLog::create([
            'campaign_id' => $this->campaign->id,
            'customer_id' => $this->customer->id,
            'status' => $status,
        ]);
    }
}
