<?php

namespace CommissionFeeTest\Unit;

use CommissionFee\CommisssionFeeCalculateStrategy\CacheInStrategy;
use CommissionFee\Currency;
use CommissionFee\Operation\Operation;
use CommissionFee\Operation\OperationTypeCashIn;
use PHPUnit\Framework\TestCase;

class CacheInStrategyTest extends TestCase
{
    public function providerCalculateRegularFee()
    {
        return [
            [1000, 0.3],
            [200, 0.06],
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
        $strategy = new CacheInStrategy();

        $this->assertSame(
            $strategy->calculate($operation),
            $expectedFee,
            "Commissions for cash in - 0.03% therefore for ". $amount . " should be ". $expectedFee
        );
    }
    public function testCalculateAboveMaxFee()
    {
        $operation = new Operation(
            new OperationTypeCashIn(),
            1000000000000000,
            new Currency('EUR')
        );
        $strategy = new CacheInStrategy();

        $this->assertSame($strategy->calculate($operation), CacheInStrategy::MAX_COMMISSION);
    }
}
