<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

use CommissionFee\Operation\Operation;

class CacheInStrategy extends CommissionFeeStrategy
{
    const MAX_COMMISSION = 5.0;
    const COMMISSION_FEE_PERCENT = 0.0003;

    public function calculate(Operation $operation): float
    {
        $fee = parent::calculate($operation);

        if ($fee > static::MAX_COMMISSION) {
            $fee = static::MAX_COMMISSION;
        }

        return $fee;
    }
}
