<?php

namespace FatherShop\EmailCampaigns\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerFilterRequest extends FormRequest
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
            'status' => 'nullable|string|in:Paid,Grace period,Expired',
            'days_to_expiry' => 'nullable|integer|min:1',
            'plan_expires_before' => 'nullable|date_format:Y-m-d',
            'plan_expires_after' => 'nullable|date_format:Y-m-d',
        ];
    }
}