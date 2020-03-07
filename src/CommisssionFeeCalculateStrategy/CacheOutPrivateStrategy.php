<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

use CommissionFee\Operation\Operation;

class CacheOutPrivateStrategy implements CommissionFeeStrategyInterface
{
    const COMMISSION_FEE_PERCENT = 0.003;
    const MIN_FEEABLE_AMOUNT = 1000;

    private Operation $operation;

    public function __construct(Operation $operation)
    {
        $this->operation = $operation;
    }

    public function calculate(): float
    {
        $amount = $this->operation->getAmount();

        if ($amount <= self::MIN_FEEABLE_AMOUNT) {
            return 0.0;
        }

        $amount = $amount - 1000;
        $fee = $amount * self::COMMISSION_FEE_PERCENT;

        return $fee;
    }
}
