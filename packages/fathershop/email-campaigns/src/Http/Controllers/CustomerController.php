<?php

namespace FatherShop\EmailCampaigns\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use FatherShop\EmailCampaigns\Services\CustomerService;
use FatherShop\EmailCampaigns\Http\Requests\CustomerFilterRequest;

class CustomerController extends Controller
{
    /**
     * The customer service instance.
     *
     * @var \FatherShop\EmailCampaigns\Services\CustomerService
     */
    protected $customerService;

    /**
     * Create a new controller instance.
     *
     * @param  \FatherShop\EmailCampaigns\Services\CustomerService  $customerService
     * @return void
     */
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of all customers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $customers = $this->customerService->getAllPaginated($perPage);

        return response()->json([
            'data' => $customers,
            'message' => 'Customers retrieved successfully'
        ]);
    }

    /**
     * Filter customers based on specified criteria.
     *
     * @param  \FatherShop\EmailCampaigns\Http\Requests\CustomerFilterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(CustomerFilterRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $customers = $this->customerService->filterCustomers($validated);

        return response()->json([
            'data' => $customers,
            'message' => 'Customers filtered successfully',
            'filter_criteria' => $validated
        ]);
    }
}