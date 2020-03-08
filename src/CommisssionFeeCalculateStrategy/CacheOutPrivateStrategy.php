<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

use CommissionFee\Operation\Operation;

class CacheOutPrivateStrategy extends CommissionFeeStrategy
{
    const MIN_FEEABLE_AMOUNT = 1000;

    public function calculate(Operation $operation): float
    {
        $amount = $operation->getAmount();

        if ($amount <= static::MIN_FEEABLE_AMOUNT) {
            return 0.0;
        }

        $amount = $amount - 1000;

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
