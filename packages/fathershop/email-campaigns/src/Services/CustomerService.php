<?php

namespace FatherShop\EmailCampaigns\Services;

use FatherShop\EmailCampaigns\Contracts\CustomerRepositoryInterface;

class CustomerService
{
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * CustomerService constructor.
     *
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Get all customers.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCustomers()
    {
        return $this->customerRepository->all();
    }

    /**
     * Get a customer by ID.
     *
     * @param int $id
     * @return \FatherShop\EmailCampaigns\Models\Customer|null
     */
    public function getCustomer($id)
    {
        return $this->customerRepository->find($id);
    }

    /**
     * Filter customers based on criteria.
     *
     * @param array $criteria
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filterCustomers(array $criteria)
    {
        return $this->customerRepository->filterByCriteria($criteria);
    }

    /**
     * Count customers based on filter criteria.
     *
     * @param array $criteria
     * @return int
     */
    public function countFilteredCustomers(array $criteria)
    {
        return $this->customerRepository->filterByCriteria($criteria)->count();
    }

    /**
     * Get customers with specific status.
     *
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCustomersByStatus($status)
    {
        return $this->customerRepository->filterByStatus($status);
    }

    /**
     * Get customers with plans expiring within X days.
     *
     * @param int $days
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCustomersByExpiryDate($days)
    {
        return $this->customerRepository->filterByExpiryDate($days);
    }
}