***FatherShop Email Campaigns***
A Laravel package for managing and sending email campaigns to customers with filtering capabilities.

Features
Import and manage customers
Create and store email campaigns with HTML or plain text content
Filter audience based on customer status and plan expiry date
Send emails to filtered customers with tracking
API-first approach for maximum flexibility
Asynchronous email processing via Laravel queues
SendGrid integration out of the box
Requirements
PHP 7.4 or higher
Laravel 8.0 or higher
Database (MySQL, PostgreSQL, etc.)
Queue system configured for Laravel
Installation
1. Install the package via Composer
bash
composer require fathershop/email-campaigns
2. Publish and run the migrations
bash
php artisan email-campaigns:install
This will:

Publish the configuration file
Publish the migrations
Run the migrations to create the necessary tables
3. Configure your mail provider
Update your .env file with the appropriate mail settings:

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailersend.net
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your@email.com
MAIL_FROM_NAME="Your Name"
For SendGrid, add your API key:

SENDGRID_API_KEY=your_sendgrid_api_key
4. Configure queue for async processing
QUEUE_CONNECTION=database
EMAIL_CAMPAIGNS_QUEUE=emails
Usage
API Endpoints
The package provides the following API endpoints:

Campaigns
GET /api/email-campaigns/campaigns - List all campaigns
POST /api/email-campaigns/campaigns - Create a new campaign
GET /api/email-campaigns/campaigns/{id} - Get a specific campaign
PUT /api/email-campaigns/campaigns/{id} - Update a campaign
DELETE /api/email-campaigns/campaigns/{id} - Delete a campaign
POST /api/email-campaigns/campaigns/{id}/send - Send a campaign to filtered audience
Customers
GET /api/email-campaigns/customers - List all customers
POST /api/email-campaigns/customers/filter - Filter customers based on criteria
Creating a Campaign
http
POST /api/email-campaigns/campaigns
Content-Type: application/json

{
  "title": "Monthly Newsletter",
  "subject": "Your Monthly Update",
  "body": "<p>Hello {{name}},</p><p>Your plan expires on {{expiry_date}}.</p>",
  "is_html": true,
  "customer_filter": {
    "status": "Paid",
    "days_to_expiry": 30
  }
}
Filtering Customers
http
POST /api/email-campaigns/customers/filter
Content-Type: application/json

{
  "status": "Paid",
  "days_to_expiry": 30
}
Sending a Campaign
http
POST /api/email-campaigns/campaigns/1/send
Content-Type: application/json

{
  "filter": {
    "status": "Paid",
    "days_to_expiry": 30
  }
}
Programmatic Usage
You can also use the package programmatically in your Laravel application:

php
use FatherShop\EmailCampaigns\Facades\EmailCampaigns;

// Create a campaign
$campaign = EmailCampaigns::createCampaign([
    'title' => 'Special Offer',
    'subject' => 'Limited Time Offer Inside',
    'body' => '<p>Hello {{name}},</p><p>Check out our special offer!</p>',
    'is_html' => true,
]);

// Send the campaign with filters
EmailCampaigns::sendCampaign($campaign, [
    'status' => 'Paid',
    'days_to_expiry' => 30,
]);
Email Templates
The package supports template variables that will be replaced with actual customer data:

{{name}} - Customer's name
{{email}} - Customer's email
{{status}} - Customer's status
{{expiry_date}} - Customer's plan expiry date
Testing
bash
composer test
License
The MIT License (MIT). Please see License File for more information.

