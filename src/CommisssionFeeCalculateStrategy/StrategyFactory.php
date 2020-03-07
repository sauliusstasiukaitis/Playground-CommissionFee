<?php

namespace CommissionFee\CommisssionFeeCalculateStrategy;

use CommissionFee\CommissionFeeContext;
use CommissionFee\Operation\OperationTypeCashIn;

class StrategyFactory
{
    /**
     * @ToDo: implement cache out for 4th and upcoming times.
     */
    public function create(CommissionFeeContext $context): CommissionFeeStrategyInterface
    {
        $operationType = $context->getOperation()->getOperationType();

        if ($operationType instanceof OperationTypeCashIn) {
            return new CacheInStrategy();
        }

        if ($context->getUser()->isCompany()) {
            return new CacheOutCompanyStrategy();
        }

        return new CacheOutPrivateStrategy();
    }
}
