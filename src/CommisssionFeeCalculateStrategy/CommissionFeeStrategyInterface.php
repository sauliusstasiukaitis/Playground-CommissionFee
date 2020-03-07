<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

use CommissionFee\Operation\Operation;

interface CommissionFeeStrategyInterface
{
    public function calculate(Operation $operation): float;
}
