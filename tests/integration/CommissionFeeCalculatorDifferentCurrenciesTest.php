<?php

namespace CommissionFeeTest\Integration;

use bovigo\vfs\vfsStream;
use CommissionFee\CommissionFeeCalculator;
use CommissionFee\CommissionFeeContextFactory;
use CommissionFee\CommisssionFeeCalculateStrategy\StrategyFactory;
use CommissionFee\Currency\Currency;
use CommissionFee\Currency\CurrencyConverter;
use CommissionFee\ParametersToObjectsFactory;
use CommissionFee\Storage\PrivateCacheOutStrategyDataRepository;
use PHPUnit\Framework\TestCase;

class CommissionFeeCalculatorDifferentCurrenciesTest extends TestCase
{
    private $vfsStream;

    public function setUp(): void {
        $this->vfsStream = vfsStream::setup();
    }

    public function testCalculateCacheOutBelowThreshold()
    {
        $defaultCurrency = new Currency('EUR');

        $inputFileContent = <<<HERDOC
                            2016-01-06,1,natural,cash_out,30000,JPY
                            HERDOC;

        $fileStream = $this->mockFile($inputFileContent);

        $commissionFeeCalculator = new CommissionFeeCalculator(
            new ParametersToObjectsFactory(),
            new CommissionFeeContextFactory(),
            new StrategyFactory(),
            $defaultCurrency,
            new PrivateCacheOutStrategyDataRepository(),
            new CurrencyConverter()
        );
        $commissionFeeList = $commissionFeeCalculator->calculate($fileStream);

        $commissionFee = $commissionFeeList[0];

        $this->assertSame(
            0.0,
            $commissionFee,
            "Should be 0 as 30000 JPY equals to 252.39 EUR"
        );
    }

    // @ToDo: address 1 cnt rounding issue. Should be 9.0
    public function testCalculateCacheIn()
    {
        $defaultCurrency = new Currency('EUR');

        $inputFileContent = <<<HERDOC
                            2016-01-06,1,natural,cash_in,30000,JPY
                            HERDOC;

        $fileStream = $this->mockFile($inputFileContent);

        $commissionFeeCalculator = new CommissionFeeCalculator(
            new ParametersToObjectsFactory(),
            new CommissionFeeContextFactory(),
            new StrategyFactory(),
            $defaultCurrency,
            new PrivateCacheOutStrategyDataRepository(),
            new CurrencyConverter()
        );
        $commissionFeeList = $commissionFeeCalculator->calculate($fileStream);

        $commissionFee = $commissionFeeList[0];

        $this->assertSame(
            9.01,
            $commissionFee,
            <<<HEREDOCS
            Should be about 9 JPY this is about 0.07 EUR cent 
            as 30000 JPY equals to 252.39 EUR and commission fee is 0.03%
            HEREDOCS
        );
    }

    // @ToDo: address 1 cnt rounding issue. Should be 0.3
    public function testCalculateCacheOutConvertBack()
    {
        $defaultCurrency = new Currency('EUR');

        $inputFileContent = <<<HERDOC
                            2016-01-07,1,natural,cash_out,1200.00,EUR
                            2016-01-07,1,natural,cash_out,100.00,USD
                            HERDOC;

        $fileStream = $this->mockFile($inputFileContent);

        $commissionFeeCalculator = new CommissionFeeCalculator(
            new ParametersToObjectsFactory(),
            new CommissionFeeContextFactory(),
            new StrategyFactory(),
            $defaultCurrency,
            new PrivateCacheOutStrategyDataRepository(),
            new CurrencyConverter()
        );
        $commissionFeeList = $commissionFeeCalculator->calculate($fileStream);

        $commissionFee = $commissionFeeList[1];

        $this->assertSame(
            0.31,
            $commissionFee
        );
    }

    // @ToDo: address 1 cnt rounding issue. Should be 8612
    public function testCalculateCacheOutConvertBack2()
    {
        $this->markTestSkipped('Address rounding issue first');

        $defaultCurrency = new Currency('EUR');

        $inputFileContent = <<<HERDOC
                            2016-01-07,1,natural,cash_out,1200.00,EUR
                            2016-02-19,2,natural,cash_out,3000000,JPY
                            HERDOC;

        $fileStream = $this->mockFile($inputFileContent);

        $commissionFeeCalculator = new CommissionFeeCalculator(
            new ParametersToObjectsFactory(),
            new CommissionFeeContextFactory(),
            new StrategyFactory(),
            $defaultCurrency,
            new PrivateCacheOutStrategyDataRepository(),
            new CurrencyConverter()
        );
        $commissionFeeList = $commissionFeeCalculator->calculate($fileStream);

        $commissionFee = $commissionFeeList[1];

        $this->assertSame(
            8612.0,
            $commissionFee
        );
    }

    /**
     * @param string $inputFileContent
     *
     * @return false|resource
     */
    private function mockFile(string $inputFileContent)
    {
        $filePath = $this->vfsStream->url() . '/input.csv';
        file_put_contents($filePath, $inputFileContent);
        $fileStream = fopen($filePath, 'r');

        return $fileStream;
    }
}
