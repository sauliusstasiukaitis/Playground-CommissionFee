<?php

namespace CommissionFeeTest;

use CommissionFee\CommissionFeeCalculator;
use CommissionFee\CommissionFeeContextFactory;
use CommissionFee\CommisssionFeeCalculateStrategy\StrategyFactory;
use CommissionFee\Currency;
use bovigo\vfs\vfsStream;
use CommissionFee\ParametersToObjectsFactory;

class CommissionFeeCalculatorTest extends \PHPUnit\Framework\TestCase
{
    private $vfsStream;

    public function setUp(): void {
        $this->vfsStream = vfsStream::setup();
    }

    public function testCalculateCashOutPrivate()
    {
        $defaultCurrency = new Currency('EUR');

        $inputFileContent = <<<HERDOC
                            2014-12-31,4,natural,cash_out,1200.00,EUR
                            HERDOC;

        $filePath = $this->vfsStream->url()  . '/input.csv';
        file_put_contents($filePath, $inputFileContent);
        $fileStream = fopen($filePath, 'r');

        $commissionFeeCalculator = new CommissionFeeCalculator(
            new ParametersToObjectsFactory(),
            new CommissionFeeContextFactory(),
            new StrategyFactory(),
            $defaultCurrency
        );
        $commissionFeeList = $commissionFeeCalculator->calculate($fileStream);

        $commissionFee = $commissionFeeList[0];

        $this->assertSame(
            0.6,
            $commissionFee,
            "Commissions for cash in - 0.3% therefore for 1200 should be 0.6"
        );
    }
}
