<?php

namespace CommissionFee\Storage;

use CommissionFee\CommisssionFeeCalculateStrategy\CacheOutPrivateStrategy;
use CommissionFee\Customer;

/**
 * Simple way to store data in memory to keep track if user reach a week limit.
 * This implementation might be changed to store data in external system etc. Redis or Mysql
 * this would allow to track same user behavior in between calls.
 */
class PrivateCacheOutStrategyDataRepository implements StrategyDataRepositoryInterface
{
    private array $customerData;

    public function addEntry(
        Customer $user,
        float $amount,
        int $dateTimestamp
    ): void
    {
        $lastMondayTimestamp = $this->getPeriodBeginningTimestamp($dateTimestamp);

        $userId = $user->getId();
        if (!isset($this->customerData[$userId][$lastMondayTimestamp])) {
            $this->createNewEntry($userId, $amount, $lastMondayTimestamp);
        } else {
            $this->updateEntry($userId, $amount, $lastMondayTimestamp);
        }
    }

    public function getDataByUserIdAndDate(int $userId, int $dateTimeStamp): PrivateCacheOutStrategyDataEntity
    {
        $lastMondayTimestamp = $this->getPeriodBeginningTimestamp($dateTimeStamp);

        return
            $this->customerData[$userId][$lastMondayTimestamp] ??
            new PrivateCacheOutStrategyDataNullEntity();
    }

    private function getPeriodBeginningTimestamp(int $timeStamp): int
    {
        return CacheOutPrivateStrategy::getPeriodBeginningTimestamp($timeStamp);
    }

    private function createNewEntry(int $userId, float $amount, int $lastMondayTimestamp): void
    {
        $this->customerData[$userId][$lastMondayTimestamp] =
            new PrivateCacheOutStrategyDataEntity($userId, $amount, 1);
    }

    private function updateEntry(int $userId, float $amount, int $lastMondayTimestamp): void
    {
        /** @var PrivateCacheOutStrategyDataEntity $customerData */
        $customerData = $this->customerData[$userId][$lastMondayTimestamp];
        $customerData->increaseTransactionCount(1);
        $customerData->increaseAmount($amount);
    }
}
