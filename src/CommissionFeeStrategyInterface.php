<?php

namespace CommissionFee;

interface CommissionFeeStrategyInterface
{
    public function calculate(): float;
}
