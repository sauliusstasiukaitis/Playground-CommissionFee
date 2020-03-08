<?php

namespace CommissionFee;

use CommissionFee\Operation\Operation;
use CommissionFee\Storage\PrivateCacheOutStrategyDataRepository;

class CommissionFeeContextFactory
{
    public function create(
        int $dateTimeStamp,
        Customer $customer,
        Operation $operations,
        PrivateCacheOutStrategyDataRepository $customerData
    ): CommissionFeeContext
    {
        return new CommissionFeeContext(
            $dateTimeStamp,
            $customer,
            $operations,
            $customerData
        );
    }
}
