<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function import(Request $request)
    {
        $data = $request->validate([
            'customers' => 'required|array',
            'customers.*.name' => 'required',
            'customers.*.email' => 'required|email',
            'customers.*.phone_number' => 'required',
            'customers.*.status' => 'required|in:Paid,Grace period,Expired',
            'customers.*.plan_expiry_date' => 'required|date',
        ]);

        foreach ($data['customers'] as $customer) {
            Customer::create($customer);
        }

        return response()->json(['message' => 'Customers imported']);
    }

    public function filter(Request $request)
    {
        $query = Customer::query();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->days_to_expiry) {
            $query->whereDate('plan_expiry_date', '<=', now()->addDays($request->days_to_expiry));
        }

        return response()->json($query->get());
    }

}
