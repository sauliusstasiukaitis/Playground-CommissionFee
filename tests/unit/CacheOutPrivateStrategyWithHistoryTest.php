<?php

namespace CommissionFeeTest\Unit;

use CommissionFee\CommisssionFeeCalculateStrategy\CacheOutPrivateStrategy;
use CommissionFee\Currency\Currency;
use CommissionFee\Operation\Operation;
use CommissionFee\Operation\OperationTypeCashOut;
use CommissionFee\Storage\PrivateCacheOutStrategyDataEntity;
use PHPUnit\Framework\TestCase;

class CacheOutPrivateStrategyWithHistoryTest extends TestCase
{
    public function providerCalculateRegularFee()
    {
        return [
            [1200, 3.6],
            [100, 0.3],
        ];
    }

    /**
     * @param float $amount
     * @param float $expectedFee
     *
     * @dataProvider providerCalculateRegularFee
     */
    public function testCalculateRegularFeeWhenThresholdAmountWithdrawnPreviously(float $amount, float $expectedFee)
    {
        $operation = new Operation(
            new OperationTypeCashOut(),
            $amount,
            new Currency('EUR')
        );
        $strategy = new CacheOutPrivateStrategy(new PrivateCacheOutStrategyDataEntity(
            1,
            10000,
            1
        ));

        $this->assertSame(
            $strategy->calculate($operation),
            $expectedFee,
            "Commissions for cash in - 0.3% therefore for ". $amount . " should be ". $expectedFee
        );
    }

    /**
     * @param float $amount
     * @param float $expectedFee
     *
     * @dataProvider providerCalculateRegularFee
     */
    public function testCalculateRegularFeeWhenWithdrawnCountReachedPreviously(float $amount, float $expectedFee)
    {
        $operation = new Operation(
            new OperationTypeCashOut(),
            $amount,
            new Currency('EUR')
        );
        $strategy = new CacheOutPrivateStrategy(new PrivateCacheOutStrategyDataEntity(
            1,
            100,
            3
        ));

        $this->assertSame(
            $strategy->calculate($operation),
            $expectedFee,
            "Commissions for cash in - 0.3% therefore for ". $amount . " should be ". $expectedFee
        );
    }

    public function testCalculateRegularFeeWhenPartlyThresholdAmountWithdrawnPreviously()
    {
        $amount = 200;
        $expectedFee = 0.6;

        $operation = new Operation(
            new OperationTypeCashOut(),
            $amount,
            new Currency('EUR')
        );
        $strategy = new CacheOutPrivateStrategy(new PrivateCacheOutStrategyDataEntity(
            1,
            1000,
            1
        ));

        $this->assertSame(
            $strategy->calculate($operation),
            $expectedFee,
            "Commissions for cash in - 0.3% therefore for ". $amount . " should be ". $expectedFee
        );
    }

    public function testCalculateRegularFeeWhenBelowThreshold()
    {
        $amount = 200;
        $expectedFee = 0.0;

        $operation = new Operation(
            new OperationTypeCashOut(),
            $amount,
            new Currency('EUR')
        );
        $strategy = new CacheOutPrivateStrategy(new PrivateCacheOutStrategyDataEntity(
            1,
            800,
            1
        ));

        $this->assertSame(
            $strategy->calculate($operation),
            $expectedFee
        );
    }
}
