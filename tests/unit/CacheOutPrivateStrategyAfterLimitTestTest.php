<?php

namespace CommissionFeeTest\Unit;

use CommissionFee\CommisssionFeeCalculateStrategy\CacheOutPrivateStrategyAfterLimit;
use CommissionFee\Currency\Currency;
use CommissionFee\Operation\Operation;
use CommissionFee\Operation\OperationTypeCashOut;
use PHPUnit\Framework\TestCase;

class CacheOutPrivateStrategyAfterLimitTest extends TestCase
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
    public function testCalculateRegularFee(float $amount, float $expectedFee)
    {
        $operation = new Operation(
            new OperationTypeCashOut(),
            $amount,
            new Currency('EUR')
        );
        $strategy = new CacheOutPrivateStrategyAfterLimit();

        $this->assertSame(
            $strategy->calculate($operation),
            $expectedFee,
            "Commissions for cash in - 0.3% therefore for ". $amount . " should be ". $expectedFee
        );
    }
}
