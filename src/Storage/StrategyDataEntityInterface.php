<?php

namespace CommissionFee\Storage;

/**
 * Different entity types might be used when storing data for private/company/cache in/cache out.
 */
interface StrategyDataEntityInterface
{
    public function __construct(int $customerId, float $amount, int $transactionCount);
}
