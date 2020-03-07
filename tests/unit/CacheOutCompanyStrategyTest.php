<?php

namespace CommissionFeeTest;

use CommissionFee\CacheOutCompanyStrategy;
use CommissionFee\Currency;
use CommissionFee\Operation\Operation;
use CommissionFee\Operation\OperationTypeCashOut;
use PHPUnit\Framework\TestCase;

class CacheOutCompanyStrategyTest extends TestCase
{
    public function testCalculateBelowThreshold()
    {
        $operation = new Operation(
            new OperationTypeCashOut(),
            1,
            new Currency('EUR')
        );
        $strategy = new CacheOutCompanyStrategy($operation);

        $this->assertSame($strategy->calculate(), CacheOutCompanyStrategy::MIN_COMMISSION);
    }
    public function providerCalculateRegularFee()
    {
        return [
            [1000, 3]
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
        $strategy = new CacheOutCompanyStrategy($operation);

        $this->assertSame(
            $strategy->calculate(),
            $expectedFee,
            "Commissions for cash out - 0.3% therefore for ". $amount . " should be ". $expectedFee
        );
    }
}
