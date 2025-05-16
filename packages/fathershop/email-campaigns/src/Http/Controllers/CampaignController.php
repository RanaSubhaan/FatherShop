<?php

namespace FatherShop\EmailCampaigns\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use FatherShop\EmailCampaigns\Models\Campaign;
use FatherShop\EmailCampaigns\Services\CampaignService;
use FatherShop\EmailCampaigns\Http\Requests\CampaignRequest;

class CampaignController extends Controller
{
    /**
     * The campaign service instance.
     *
     * @var \FatherShop\EmailCampaigns\Services\CampaignService
     */
    protected $campaignService;

    /**
     * Create a new controller instance.
     *
     * @param  \FatherShop\EmailCampaigns\Services\CampaignService  $campaignService
     * @return void
     */
    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    /**
     * Display a listing of campaigns.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $campaigns = $this->campaignService->getAllPaginated($perPage);

        return response()->json([
            'data' => $campaigns,
            'message' => 'Campaigns retrieved successfully'
        ]);
    }

    /**
     * Store a newly created campaign.
     *
     * @param  \FatherShop\EmailCampaigns\Http\Requests\CampaignRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CampaignRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $campaign = $this->campaignService->create($validated);

        return response()->json([
            'data' => $campaign,
            'message' => 'Campaign created successfully'
        ], 201);
    }

    /**
     * Display the specified campaign.
     *
     * @param  \FatherShop\EmailCampaigns\Models\Campaign  $campaign
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Campaign $campaign): JsonResponse
    {
        return response()->json([
            'data' => $campaign,
            'message' => 'Campaign retrieved successfully'
        ]);
    }

    /**
     * Update the specified campaign.
     *
     * @param  \FatherShop\EmailCampaigns\Http\Requests\CampaignRequest  $request
     * @param  \FatherShop\EmailCampaigns\Models\Campaign  $campaign
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CampaignRequest $request, Campaign $campaign): JsonResponse
    {
        $validated = $request->validated();
        $updatedCampaign = $this->campaignService->update($campaign, $validated);

        return response()->json([
            'data' => $updatedCampaign,
            'message' => 'Campaign updated successfully'
        ]);
    }

    /**
     * Remove the specified campaign.
     *
     * @param  \FatherShop\EmailCampaigns\Models\Campaign  $campaign
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Campaign $campaign): JsonResponse
    {
        $this->campaignService->delete($campaign);

        return response()->json([
            'message' => 'Campaign deleted successfully'
        ]);
    }

    /**
     * Send the campaign to filtered customers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \FatherShop\EmailCampaigns\Models\Campaign  $campaign
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request, Campaign $campaign): JsonResponse
    {
        $filters = $request->input('filter', []);
        $result = $this->campaignService->sendCampaign($campaign, $filters);

        return response()->json([
            'message' => 'Campaign sending process started',
            'data' => [
                'recipients_count' => $result['count'],
                'filter_criteria' => $filters
            ]
        ]);
    }
}