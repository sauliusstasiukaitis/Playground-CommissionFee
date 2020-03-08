<?php

namespace CommissionFee;

class PrivateCacheOutStrategyDataEntity
{
    private int $userId;
    private float $amount;
    private int $transactionCount;

    public function __construct(int $userId, float $amount, int $transactionCount)
    {
        $this->userId = $userId;
        $this->amount = $amount;
        $this->transactionCount = $transactionCount;
    }

    public function getUserId(): int
    {
        return $this->userId;
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
