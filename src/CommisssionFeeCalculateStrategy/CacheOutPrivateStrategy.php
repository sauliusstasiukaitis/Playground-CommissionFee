<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

use CommissionFee\Operation\Operation;
use CommissionFee\Storage\StrategyDataEntityInterface;

class CacheOutPrivateStrategy extends CommissionFeeStrategy
{
    const CUSTOMER_CACHE_OUT_AMOUNT_LIMIT = 1000;
    const CUSTOMER_FREE_CASH_OUT_LIMIT = 3;

    private StrategyDataEntityInterface $customerData;

    public function __construct(StrategyDataEntityInterface $customerData)
    {
        $this->customerData = $customerData;
    }

    public function calculate(Operation $operation): float
    {
        if (
            $this->customerData->getTransactionCount() >= static::CUSTOMER_FREE_CASH_OUT_LIMIT ||
            $this->customerData->getAmount() > static::CUSTOMER_CACHE_OUT_AMOUNT_LIMIT
        ) {

            return parent::calculate($operation);
        }

        $amount = $operation->getAmount();

        if (($amount + $this->customerData->getAmount()) <= static::CUSTOMER_CACHE_OUT_AMOUNT_LIMIT) {
            return 0.0;
        }

        $amount = $amount - static::CUSTOMER_CACHE_OUT_AMOUNT_LIMIT + $this->customerData->getAmount();

        $fee = parent::calculate(new Operation(
            $operation->getOperationType(),
            $amount,
            $operation->getCurrency()
        ));

        return $fee;
    }

    /**
     * @param int $timeStamp
     *
     * @return int Timestamp of the last Monday from the provided time stamp.
     *
     * @throws \Exception when provided timestamp is not valid.
     */
    public static function getPeriodBeginningTimestamp(int $timeStamp): int
    {
        $lastMondayTimestamp = strtotime('last monday', $timeStamp);

        if (
            $lastMondayTimestamp === false ||
            $lastMondayTimestamp < 0
        ) {
            throw new \Exception(
                <<<HEREDOCS
                    Time stamp conversion failed! 
                    Not possible to get a correct timestamp for the Monday of provided timestamp: 
                    HEREDOCS
                . $timeStamp
            );
        }

        return $lastMondayTimestamp;
    }
}
