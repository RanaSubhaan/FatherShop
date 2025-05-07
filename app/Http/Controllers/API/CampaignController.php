<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        $campaign = Campaign::create($data);

        return response()->json($campaign);
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'status' => 'nullable|in:Paid,Grace period,Expired',
            'days_to_expiry' => 'nullable|integer',
        ]);

        $campaign = Campaign::find($data['campaign_id']);

        $customers = Customer::query();
        if ($data['status']) {
            $customers->where('status', $data['status']);
        }
        if ($data['days_to_expiry']) {
            $customers->whereDate('plan_expiry_date', '<=', now()->addDays($data['days_to_expiry']));
        }

        foreach ($customers->get() as $customer) {
            dispatch(new SendCampaignEmail($campaign, $customer));
        }

        return response()->json(['message' => 'Emails are being sent.']);
    }

}
