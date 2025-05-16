<?php

namespace FatherShop\EmailCampaigns\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface CustomerRepositoryInterface
{
    /**
     * Get all customers.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Get customer by ID.
     *
     * @param int $id
     * @return \FatherShop\EmailCampaigns\Models\Customer|null
     */
    public function find($id);

    /**
     * Filter customers by status.
     *
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filterByStatus($status);

    /**
     * Filter customers by expiry date.
     *
     * @param int $days
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filterByExpiryDate($days);

    /**
     * Filter customers by combined criteria.
     *
     * @param array $criteria
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filterByCriteria(array $criteria);
}