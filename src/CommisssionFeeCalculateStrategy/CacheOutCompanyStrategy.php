<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

use CommissionFee\Operation\Operation;

class CacheOutCompanyStrategy implements CommissionFeeStrategyInterface
{
    const MIN_COMMISSION = 0.5;
    const COMMISSION_FEE_PERCENT = 0.003;

    public function calculate(Operation $operation): float
    {
        $amount = $operation->getAmount();

        $fee = $amount * self::COMMISSION_FEE_PERCENT;

        if ($fee < self::MIN_COMMISSION) {
            $fee = self::MIN_COMMISSION;
        }

        return $fee;
    }
}
