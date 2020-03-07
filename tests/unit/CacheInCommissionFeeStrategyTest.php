<?php

namespace CommissionFeeTest;

use CommissionFee\CacheInCommissionFeeStrategy;
use CommissionFee\Currency;
use CommissionFee\Operation;
use CommissionFee\OperationTypeCashIn;
use PHPUnit\Framework\TestCase;

class CacheInCommissionFeeStrategyTest extends TestCase
{
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
            new OperationTypeCashIn(),
            $amount,
            new Currency('EUR')
        );
        $strategy = new CacheInCommissionFeeStrategy($operation);

        $this->assertSame(
            $strategy->calculate(),
            $expectedFee,
            "Commissions for cash in - 0.3% therefore for ". $amount . " should be ". $expectedFee
        );
    }
    public function testCalculateAboveMaxFee()
    {
        $operation = new Operation(
            new OperationTypeCashIn(),
            1000000000000000,
            new Currency('EUR')
        );
        $strategy = new CacheInCommissionFeeStrategy($operation);

        $this->assertSame($strategy->calculate(), CacheInCommissionFeeStrategy::MAX_COMMISSION);
    }
}
