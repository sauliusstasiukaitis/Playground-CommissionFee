<?php

namespace CommissionFee;

class UserDataRepository
{
    const DATE = 'date';
    const AMOUNT = 'amount';

    private array $userData;

    public function addEntry(
        User $user,
        float $amount,
        int $dateTimestamp
    ): void
    {
        /**
         * @ToDo: organise data by week.
         * If entry for that week already exists - add sum on top.
         * Calculate amount of occurrence during a week.
         */
        $this->userData[$user->getId()][] = [
            static::DATE => $dateTimestamp,
            static::AMOUNT => $amount
        ];
    }

    public function getDataByUserId(int $userId)
    {
        return $this->userData[$userId] ?? null;
    }
}
