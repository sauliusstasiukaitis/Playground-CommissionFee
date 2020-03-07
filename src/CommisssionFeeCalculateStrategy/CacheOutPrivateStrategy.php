<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

use CommissionFee\Operation\Operation;

class CacheOutPrivateStrategy extends CommissionFeeStrategy
{
    const MIN_FEEABLE_AMOUNT = 1000;

    public function calculate(Operation $operation): float
    {
        $amount = $operation->getAmount();

        if ($amount <= static::MIN_FEEABLE_AMOUNT) {
            return 0.0;
        }

        $amount = $amount - 1000;

        $fee = parent::calculate(new Operation(
            $operation->getOperationType(),
            $amount,
            $operation->getCurrency()
        ));

        return $fee;
    }
}
