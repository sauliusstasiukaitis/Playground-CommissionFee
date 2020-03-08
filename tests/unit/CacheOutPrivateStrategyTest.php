<?php

namespace CommissionFeeTest\Unit;

use CommissionFee\CommisssionFeeCalculateStrategy\CacheOutPrivateStrategy;
use CommissionFee\Currency\Currency;
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
        $strategy = new CacheOutPrivateStrategy();

        $this->assertSame(
            $strategy->calculate($operation),
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
        $strategy = new CacheOutPrivateStrategy();

        $this->assertSame(
            $strategy->calculate($operation),
            $expectedFee,
            "Commissions for cash in above 1000 - 0.3% therefore for ". $amount . " should be ". $expectedFee
        );
    }

    public function testGetPeriodBeginningTimestampForCorrectDate()
    {
        $timeStamp = 1419984000; // '2014-12-29';
        $timeStampOfLastMonday = 1419811200; // '2014-12-28';

        $this->assertSame(CacheOutPrivateStrategy::getPeriodBeginningTimestamp($timeStamp), $timeStampOfLastMonday);
    }

    public function testGetPeriodBeginningThrowsExceptionForWrongTimestamp()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(<<<HEREDOCS
            Time stamp conversion failed! 
            Not possible to get a correct timestamp for the Monday of provided timestamp: 0
            HEREDOCS
        );

        CacheOutPrivateStrategy::getPeriodBeginningTimestamp(0);
    }
}
