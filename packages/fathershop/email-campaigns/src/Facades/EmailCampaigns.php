<?php

namespace FatherShop\EmailCampaigns\Facades;

use Illuminate\Support\Facades\Facade;
use FatherShop\EmailCampaigns\Services\CampaignService;

/**
 * @method static \Illuminate\Pagination\LengthAwarePaginator getAllCampaigns(int $perPage = 15)
 * @method static \FatherShop\EmailCampaigns\Models\Campaign createCampaign(array $data)
 * @method static \FatherShop\EmailCampaigns\Models\Campaign updateCampaign(\FatherShop\EmailCampaigns\Models\Campaign $campaign, array $data)
 * @method static bool deleteCampaign(\FatherShop\EmailCampaigns\Models\Campaign $campaign)
 * @method static array sendCampaign(\FatherShop\EmailCampaigns\Models\Campaign $campaign, array $filters = [])
 *
 * @see \FatherShop\EmailCampaigns\Services\CampaignService
 */
class EmailCampaigns extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CampaignService::class;
    }
}