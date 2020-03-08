<?php

namespace CommissionFee;

use CommissionFee\Operation\Operation;

class CommissionFeeContextFactory
{
    public function create(
        int $dateTimeStamp,
        User $user,
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
