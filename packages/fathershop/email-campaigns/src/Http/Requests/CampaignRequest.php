<?php

namespace FatherShop\EmailCampaigns\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'is_html' => 'boolean',
            'customer_filter' => 'nullable|array',
            'customer_filter.status' => 'nullable|string|in:Paid,Grace period,Expired',
            'customer_filter.days_to_expiry' => 'nullable|integer|min:1',
            'customer_filter.plan_expires_before' => 'nullable|date_format:Y-m-d',
            'customer_filter.plan_expires_after' => 'nullable|date_format:Y-m-d',
        ];
    }
}