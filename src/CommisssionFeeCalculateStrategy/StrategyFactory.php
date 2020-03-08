<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

use CommissionFee\CommissionFeeContext;
use CommissionFee\Operation\OperationTypeCashIn;

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

        $customerDataRepository = $context->getCustomerData();
        $customerData = $customerDataRepository->getDataByUserIdAndDate($user->getId(), $context->getDateTimeStamp());

        if (
            $customerData->getTransactionCount() >= static::USER_FREE_CASH_OUT_LIMIT ||
            $customerData->getAmount() > static::USER_CACHE_OUT_AMOUNT_LIMIT
        ) {

            $strategy = new CacheOutPrivateStrategyAfterLimit();
        } else {
            $strategy = new CacheOutPrivateStrategy();
        }

        $customerDataRepository->addEntry($user, $operation->getAmount(), $context->getDateTimeStamp());

        return $strategy;
    }
}
