<?php

namespace CommissionFee\Storage;

class PrivateCacheOutStrategyDataNullEntity extends PrivateCacheOutStrategyDataEntity
{
    public function __construct(int $userId = 0, float $amount = 0, int $transactionCount = 0)
    {
        parent::__construct(0, 0, 0);
    }
}
