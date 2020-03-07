<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

use CommissionFee\Operation\Operation;

abstract class CommissionFeeStrategy implements CommissionFeeStrategyInterface
{
    const COMMISSION_FEE_PERCENT = 0.003;

    public function calculate(Operation $operation): float
    {
        $amount = $operation->getAmount();

        $fee = $amount * static::COMMISSION_FEE_PERCENT;

        return $fee;
    }
}
