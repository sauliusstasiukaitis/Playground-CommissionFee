<?php

namespace CommissionFee;

class PrivateCacheOutStrategyDataNullEntity extends PrivateCacheOutStrategyDataEntity
{
    public function __construct()
    {
        parent::__construct(0, 0, 0);
    }
}
