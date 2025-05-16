<?php

namespace FatherShop\EmailCampaigns\Mail;

use FatherShop\EmailCampaigns\Models\Campaign;
use FatherShop\EmailCampaigns\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

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
     * Create a new message instance.
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
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Get the content - replace placeholders
        $content = $this->replacePlaceholders($this->campaign->body);

        // Set the subject
        $subject = $this->replacePlaceholders($this->campaign->subject);

        // Build the email
        $mail = $this->subject($subject)
            ->from(
                config('email-campaigns.mail.from_address'), 
                config('email-campaigns.mail.from_name')
            );

        // Set the email content based on format
        if ($this->campaign->is_html) {
            $mail->html($content);
        } else {
            $mail->text($content);
        }

        return $mail;
    }

    /**
     * Replace placeholders in content with customer data.
     *
     * @param string $content
     * @return string
     */
    protected function replacePlaceholders($content)
    {
        $placeholders = [
            '{{name}}' => $this->customer->name,
            '{{email}}' => $this->customer->email,
            '{{status}}' => $this->customer->status,
            '{{plan_expiry_date}}' => $this->customer->plan_expiry_date 
                ? $this->customer->plan_expiry_date->format('Y-m-d') 
                : '',
            '{{days_to_expiry}}' => $this->customer->plan_expiry_date 
                ? now()->diffInDays($this->customer->plan_expiry_date, false) 
                : '',
        ];

        return str_replace(
            array_keys($placeholders),
            array_values($placeholders),
            $content
        );
    }
}