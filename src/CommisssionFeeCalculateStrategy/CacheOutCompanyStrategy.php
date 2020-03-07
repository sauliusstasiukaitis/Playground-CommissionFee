<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

use CommissionFee\Operation\Operation;

class CacheOutCompanyStrategy extends CommissionFeeStrategy
{
    const MIN_COMMISSION = 0.5;

    public function calculate(Operation $operation): float
    {
        $fee = parent::calculate($operation);

        if ($fee < self::MIN_COMMISSION) {
            $fee = self::MIN_COMMISSION;
        }

        return $fee;
    }
}
