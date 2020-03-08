<?php

namespace CommissionFeeTest\Unit;

use CommissionFee\Currency;
use CommissionFee\CurrencyConverter;
use CommissionFee\Operation\Operation;
use CommissionFee\Operation\OperationTypeCashOut;
use PHPUnit\Framework\TestCase;

class CurrencyConverterTest extends TestCase
{
    public function testConvertJpyToEur()
    {
        $amountJpy = 30000;
        $amountEur = 231.61;
        $operation = new Operation(
            new OperationTypeCashOut(),
            $amountJpy,
            new Currency('JPY')
        );

        $currencyConverter = new CurrencyConverter();
        $convertOperation = $currencyConverter->convertOperation($operation, new Currency('EUR'));

        $this->assertSame(
            $amountEur,
            $convertOperation->getAmount()
        );
    }

    public function testConvertEurToJpy()
    {
        $amountJpy = 30000.44;
        $amountEur = 231.61;
        $operation = new Operation(
            new OperationTypeCashOut(),
            $amountEur,
            new Currency('EUR')
        );

        $currencyConverter = new CurrencyConverter();
        $convertOperation = $currencyConverter->convertOperation($operation, new Currency('JPY'));

        $this->assertSame(
            $amountJpy,
            $convertOperation->getAmount()
        );
    }

    public function testConvertUsdToEur()
    {
        $amountUsd = 100;
        $amountEur = 86.98;
        $operation = new Operation(
            new OperationTypeCashOut(),
            $amountUsd,
            new Currency('USD')
        );

        $currencyConverter = new CurrencyConverter();
        $convertOperation = $currencyConverter->convertOperation($operation, new Currency('EUR'));

        $this->assertSame(
            $amountEur,
            $convertOperation->getAmount()
        );
    }

    public function testConvertEurToUsd()
    {
        $amountUsd = 86.98;
        $amountEur = 100.0;
        $operation = new Operation(
            new OperationTypeCashOut(),
            $amountUsd,
            new Currency('EUR')
        );

        $currencyConverter = new CurrencyConverter();
        $convertOperation = $currencyConverter->convertOperation($operation, new Currency('USD'));

        $this->assertSame(
            $amountEur,
            $convertOperation->getAmount()
        );
    }


    public function testConvertUnknownCurrencyThrowsException()
    {
        $operation = new Operation(
            new OperationTypeCashOut(),
            100,
            new Currency('DDD')
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unknown currency convertion: DDD to EUR'
        );

        $currencyConverter = new CurrencyConverter();
        $convertOperation = $currencyConverter->convertOperation($operation, new Currency('EUR'));
    }
}
