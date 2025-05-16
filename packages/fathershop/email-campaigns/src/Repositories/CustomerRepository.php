<?php

namespace FatherShop\EmailCampaigns\Repositories;

use FatherShop\EmailCampaigns\Contracts\CustomerRepositoryInterface;
use FatherShop\EmailCampaigns\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository implements CustomerRepositoryInterface
{
    /**
     * @var Customer
     */
    protected $model;

    /**
     * CustomerRepository constructor.
     *
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        $this->model = $customer;
    }

    /**
     * Get all customers.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Get customer by ID.
     *
     * @param int $id
     * @return \FatherShop\EmailCampaigns\Models\Customer|null
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Filter customers by status.
     *
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filterByStatus($status)
    {
        return $this->model->where('status', $status)->get();
    }

    /**
     * Filter customers by expiry date.
     *
     * @param int $days
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filterByExpiryDate($days)
    {
        return $this->model->expiringWithin($days)->get();
    }

    /**
     * Filter customers by combined criteria.
     *
     * @param array $criteria
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filterByCriteria(array $criteria)
    {
        $query = $this->model->newQuery();

        // Filter by status if provided
        if (isset($criteria['status']) && !empty($criteria['status'])) {
            $query->where('status', $criteria['status']);
        }

        // Filter by expiry days if provided
        if (isset($criteria['expiry_days']) && is_numeric($criteria['expiry_days'])) {
            $days = (int) $criteria['expiry_days'];
            $today = now();
            $futureDate = now()->addDays($days);
            
            $query->whereBetween('plan_expiry_date', [$today, $futureDate]);
        }

        return $query->get();
    }
}