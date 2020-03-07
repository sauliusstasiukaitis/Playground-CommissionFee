<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

interface CommissionFeeStrategyInterface
{
    public function calculate(): float;
}
