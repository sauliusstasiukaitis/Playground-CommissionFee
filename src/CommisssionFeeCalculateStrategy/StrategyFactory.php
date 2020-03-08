<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

use CommissionFee\CommissionFeeContext;
use CommissionFee\Operation\OperationTypeCashIn;

class StrategyFactory
{
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

        $strategy = new CacheOutPrivateStrategy($customerData);

        return $strategy;
    }
}
