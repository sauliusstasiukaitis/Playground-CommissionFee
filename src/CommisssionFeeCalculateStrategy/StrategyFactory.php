<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

use CommissionFee\CommissionFeeContext;
use CommissionFee\Operation\OperationTypeCashIn;
use CommissionFee\UserDataRepository;

class StrategyFactory
{
    const USER_CACHE_OUT_AMOUNT_LIMIT = 1000;
    const USER_FREE_CASH_OUT_LIMIT = 3;

    public function create(CommissionFeeContext $context): CommissionFeeStrategyInterface
    {
        $operation = $context->getOperation();
        $operationType = $operation->getOperationType();

        if ($operationType instanceof OperationTypeCashIn) {
            return new CacheInStrategy();
        }

        $user = $context->getUser();
        if ($user->isCompany()) {
            return new CacheOutCompanyStrategy();
        }

        $userData = $context->getUserData();
        $userTransactionList = $userData->getDataByUserId($user->getId());

        if (!is_null($userTransactionList) && count($userTransactionList) >= static::USER_FREE_CASH_OUT_LIMIT) {
            $strategy = new CacheOutPrivateStrategyAfterLimit();
        } else {
            $userTransactionsSum = $this->calculateUserTransactionSum($userTransactionList);

            if ($userTransactionsSum > static::USER_CACHE_OUT_AMOUNT_LIMIT) {
                $strategy = new CacheOutPrivateStrategyAfterLimit();
            } else {
                $strategy = new CacheOutPrivateStrategy();
            }
        }

        $userData->addEntry($user, $operation->getAmount(), $context->getDateTimeStamp());

        return $strategy;
    }

    private function calculateUserTransactionSum(?array $userTransactionList)
    {
        $amount = 0;

        if (is_null($userTransactionList)) {
            return $amount;
        }

        foreach ($userTransactionList as $userTransaction) {
            $amount += $userTransaction[UserDataRepository::AMOUNT];
        }

        return $amount;
    }
}
