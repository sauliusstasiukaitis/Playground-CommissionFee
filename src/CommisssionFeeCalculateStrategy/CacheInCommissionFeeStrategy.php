<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

use CommissionFee\Operation\Operation;

class CacheInCommissionFeeStrategy implements CommissionFeeStrategyInterface
{
    const COMMISSION_FEE_PERCENT = 0.003;
    const MAX_COMMISSION = 5.0;

    public function calculate(Operation $operation): float
    {
        $amount = $operation->getAmount();

        $fee = $amount * self::COMMISSION_FEE_PERCENT;

        if ($fee > self::MAX_COMMISSION) {
            $fee = self::MAX_COMMISSION;
        }

        return $fee;
    }
}
