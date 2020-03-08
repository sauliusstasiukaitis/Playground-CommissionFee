<?php

namespace CommissionFee\Storage;

class PrivateCacheOutStrategyDataEntity implements StrategyDataEntityInterface
{
    private int $customerId;
    private float $amount;
    private int $transactionCount;

    public function __construct(int $customerId, float $amount, int $transactionCount)
    {
        $this->customerId = $customerId;
        $this->amount = $amount;
        $this->transactionCount = $transactionCount;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function increaseAmount(float $amountToIncrease): void
    {
        $this->amount += $amountToIncrease;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function increaseTransactionCount(int $amountToIncrease): void
    {
        $this->transactionCount += $amountToIncrease;
    }

    public function getTransactionCount(): int
    {
        return $this->transactionCount;
    }
}
