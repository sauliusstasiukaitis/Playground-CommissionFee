<?php

namespace CommissionFee;

use CommissionFee\Operation\Operation;
use CommissionFee\Storage\PrivateCacheOutStrategyDataRepository;

class CommissionFeeContextFactory
{
    public function create(
        int $dateTimeStamp,
        Customer $user,
        Operation $operations,
        PrivateCacheOutStrategyDataRepository $userData
    ): CommissionFeeContext
    {
        return new CommissionFeeContext(
            $dateTimeStamp,
            $user,
            $operations,
            $userData
        );
    }
}
