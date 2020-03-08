<?php

use CommissionFee\CommissionFeeContext;
use CommissionFee\CommisssionFeeCalculateStrategy\CacheInStrategy;
use CommissionFee\CommisssionFeeCalculateStrategy\CacheOutCompanyStrategy;
use CommissionFee\CommisssionFeeCalculateStrategy\CacheOutPrivateStrategy;
use CommissionFee\CommisssionFeeCalculateStrategy\StrategyFactory;
use CommissionFee\Currency\Currency;
use CommissionFee\Operation\Operation;
use CommissionFee\Operation\OperationType;
use CommissionFee\Operation\OperationTypeCashIn;
use CommissionFee\Operation\OperationTypeCashOut;
use CommissionFee\Customer;
use PHPUnit\Framework\TestCase;

class StrategyFactoryTest extends TestCase
{
    public function testCreateForCacheIn()
    {
        $operationType = new OperationTypeCashIn();
        $context = $this->getCommissionFeeContext($operationType);

        $factory = new StrategyFactory();
        $this->assertInstanceOf(CacheInStrategy::class, $factory->create($context));
    }

    public function testCreateForCacheOutPrivate()
    {
        $operationType = new OperationTypeCashOut();
        $context = $this->getCommissionFeeContext($operationType);

        $factory = new StrategyFactory();
        $this->assertInstanceOf(CacheOutPrivateStrategy::class, $factory->create($context));
    }

    public function testCreateForCacheOutCompany()
    {
        $operationType = new OperationTypeCashOut();
        $context = $this->getCommissionFeeContext($operationType, 'legal');

        $factory = new StrategyFactory();
        $this->assertInstanceOf(CacheOutCompanyStrategy::class, $factory->create($context));
    }

    public function getCommissionFeeContext(
        OperationType $operationType,
        string $userType = 'natural'
    ): CommissionFeeContext
    {
        return new CommissionFeeContext(
            strtotime('2019-01-01'),
            new Customer(6, $userType),
            new Operation(
                $operationType,
                1200,
                new Currency('EUR')
            )
        );
    }
}
