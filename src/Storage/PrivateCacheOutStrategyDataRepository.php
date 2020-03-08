<?php

namespace CommissionFee\Storage;

use CommissionFee\CommisssionFeeCalculateStrategy\CacheOutPrivateStrategy;
use CommissionFee\Customer;

/**
 * Simple way to store data in memory to keep track if customer reach a week limit.
 * This implementation might be changed to store data in external system etc. Redis or Mysql
 * this would allow to track same user behavior in between calls.
 */
class PrivateCacheOutStrategyDataRepository implements StrategyDataRepositoryInterface
{
    private array $customerData;

    public function addEntry(
        Customer $customer,
        float $amount,
        int $dateTimestamp
    ): void
    {
        $lastMondayTimestamp = $this->getPeriodBeginningTimestamp($dateTimestamp);

        $customerId = $customer->getId();
        if (!isset($this->customerData[$customerId][$lastMondayTimestamp])) {
            $this->createNewEntry($customerId, $amount, $lastMondayTimestamp);
        } else {
            $this->updateEntry($customerId, $amount, $lastMondayTimestamp);
        }
    }

    public function getDataByCustomerIdAndDate(int $customerId, int $dateTimeStamp): PrivateCacheOutStrategyDataEntity
    {
        $lastMondayTimestamp = $this->getPeriodBeginningTimestamp($dateTimeStamp);

        return
            $this->customerData[$customerId][$lastMondayTimestamp] ??
            new PrivateCacheOutStrategyDataNullEntity();
    }

    private function getPeriodBeginningTimestamp(int $timeStamp): int
    {
        return CacheOutPrivateStrategy::getPeriodBeginningTimestamp($timeStamp);
    }

    private function createNewEntry(int $customerId, float $amount, int $lastMondayTimestamp): void
    {
        $this->customerData[$customerId][$lastMondayTimestamp] =
            new PrivateCacheOutStrategyDataEntity($customerId, $amount, 1);
    }

    private function updateEntry(int $customerId, float $amount, int $lastMondayTimestamp): void
    {
        /** @var PrivateCacheOutStrategyDataEntity $customerData */
        $customerData = $this->customerData[$customerId][$lastMondayTimestamp];
        $customerData->increaseTransactionCount(1);
        $customerData->increaseAmount($amount);
    }
}
