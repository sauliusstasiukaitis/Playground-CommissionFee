<?php
/**
 * Created by PhpStorm.
 * User: saulius
 * Date: 20.3.5
 * Time: 20.54
 */

namespace CommissionFee;

use CommissionFee\Operation\Operation;
use CommissionFee\Storage\PrivateCacheOutStrategyDataRepository;

class CommissionFeeContext
{
    private int $dateTimeStamp;
    private Customer $customer;
    private Operation $operation;
    private PrivateCacheOutStrategyDataRepository $customerData;

    /**
     * CommissionFeeCalculator constructor dependent on context chooses correct strategy to
     * calculate commission fee.
     *
     * @param int       $dateTimeStamp
     * @param Customer  $customer
     * @param Operation $operation
     */
    public function __construct(
        int $dateTimeStamp,
        Customer $customer,
        Operation $operations,
        PrivateCacheOutStrategyDataRepository $customerData
    ) {
        $this->dateTimeStamp = $dateTimeStamp;
        $this->customer = $customer;
        $this->operation = $operations;
        $this->customerData = $customerData;
    }

    public function getDateTimeStamp(): int
    {
        return $this->dateTimeStamp;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getOperation(): Operation
    {
        return $this->operation;
    }

    // @ToDo: depend on abstraction.
    // This would allow to change rules and store data for all customers - including companies.
    public function getCustomerData(): PrivateCacheOutStrategyDataRepository
    {
        return $this->customerData;
    }
}
