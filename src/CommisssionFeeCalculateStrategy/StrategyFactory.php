<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

use CommissionFee\CommissionFeeContext;
use CommissionFee\Operation\OperationTypeCashIn;

class StrategyFactory
{
    const CUSTOMER_CACHE_OUT_AMOUNT_LIMIT = 1000;
    const CUSTOMER_FREE_CASH_OUT_LIMIT = 3;

    public function create(CommissionFeeContext $context): CommissionFeeStrategyInterface
    {
        $operation = $context->getOperation();
        $operationType = $operation->getOperationType();

        if ($operationType instanceof OperationTypeCashIn) {
            return new CacheInStrategy();
        }

        $customer = $context->getCustomer();
        if ($customer->isCompany()) {
            return new CacheOutCompanyStrategy();
        }

        $customerDataRepository = $context->getCustomerData();
        $customerData = $customerDataRepository->getDataByCustomerIdAndDate($customer->getId(), $context->getDateTimeStamp());

        if (
            $customerData->getTransactionCount() >= static::CUSTOMER_FREE_CASH_OUT_LIMIT ||
            $customerData->getAmount() > static::CUSTOMER_CACHE_OUT_AMOUNT_LIMIT
        ) {

            $strategy = new CacheOutPrivateStrategyAfterLimit();
        } else {
            $strategy = new CacheOutPrivateStrategy();
        }

        $customerDataRepository->addEntry($customer, $operation->getAmount(), $context->getDateTimeStamp());

        return $strategy;
    }
}
