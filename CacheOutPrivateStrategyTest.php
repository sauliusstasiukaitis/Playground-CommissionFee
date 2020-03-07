<?php

use CommissionFee\CommisssionFeeCalculateStrategy\CacheOutPrivateStrategy;
use CommissionFee\Currency;
use CommissionFee\Operation\Operation;
use CommissionFee\Operation\OperationTypeCashOut;
use PHPUnit\Framework\TestCase;

class CacheOutPrivateStrategyTest extends TestCase
{
    public function providerCalculateBelowThreshold()
    {
        return [
            [1],
            [CacheOutPrivateStrategy::MIN_FEEABLE_AMOUNT]
        ];
    }

    /**
     * @param float $amount
     *
     * @dataProvider providerCalculateBelowThreshold
     */
    public function testCalculateBelowThreshold(float $amount)
    {
        $operation = new Operation(
            new OperationTypeCashOut(),
            $amount,
            new Currency('EUR')
        );
        $strategy = new CacheOutPrivateStrategy($operation);

        $this->assertSame(
            $strategy->calculate(),
            0.0,
            "Should be 0 for a sum below threshold for amount: ". $amount
        );
    }
    public function providerCalculateRegularFee()
    {
        return [
            [1200, 0.6]
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
        $strategy = new CacheOutPrivateStrategy($operation);

        $this->assertSame(
            $strategy->calculate(),
            $expectedFee,
            "Commissions for cash in above 1000 - 0.3% therefore for ". $amount . " should be ". $expectedFee
        );
    }
}
