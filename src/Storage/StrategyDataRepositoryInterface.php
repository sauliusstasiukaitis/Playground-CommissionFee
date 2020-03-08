<?php

namespace CommissionFee\Storage;

use CommissionFee\User;

/**
 * Allows to add different repositories to store data differently etc. in Redis, MySQL.
 * Different repository types might be used when storing data for private/company/cache in/cache out.
 */
interface StrategyDataRepositoryInterface
{
    public function addEntry(
        User $user,
        float $amount,
        int $dateTimestamp
    ): void;

    public function getDataByUserIdAndDate(int $userId, int $dateTimeStamp): StrategyDataEntityInterface;
}
