<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

use CommissionFee\Operation\Operation;

class CacheInCommissionFeeStrategy implements CommissionFeeStrategyInterface
{
    private Operation $operation;

    const COMMISSION_FEE_PERCENT = 0.003;
    const MAX_COMMISSION = 5.0;

    /**
     * CachInStrategy constructor.
     * @param $operation
     */
    public function __construct(Operation $operation)
    {
        $this->operation = $operation;
    }

    public function calculate(): float
    {
        $amount = $this->operation->getAmount();

        $fee = $amount * self::COMMISSION_FEE_PERCENT;

        if ($fee > self::MAX_COMMISSION) {
            $fee = self::MAX_COMMISSION;
        }

        return $fee;
    }
}
