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

class CommissionFeeCalculatorDifferentUsersTest extends TestCase
{
    private $vfsStream;

    public function setUp(): void {
        $this->vfsStream = vfsStream::setup();
    }

    public function testCalculateSameCurrencySameCustomer()
    {
        $defaultCurrency = new Currency('EUR');

        $inputFileContent = <<<HERDOC
                            2016-01-06,1,natural,cash_out,200,EUR
                            2016-01-07,1,natural,cash_out,1000.00,EUR
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
            0.6,
            $commissionFee,
            "Commissions for cash out after 1000 is 0.3% therefore for 200 should be 0.6"
        );
    }

    public function testCalculateDifferentCurrencySingleUser()
    {
        $defaultCurrency = new Currency('EUR');

        $inputFileContent = <<<HERDOC
                            2016-01-05,1,natural,cash_out,200.00,EUR
                            2016-01-06,1,natural,cash_out,30000,JPY
                            2016-01-07,1,natural,cash_out,1000.00,EUR
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

        $commissionFee = $commissionFeeList[2];

        $this->assertSame(
            1.3,
            $commissionFee
        );
    }

    public function testCalculateDifferentCurrencyDifferentUser()
    {
        $defaultCurrency = new Currency('EUR');

        $inputFileContent = <<<HERDOC
                            2016-01-05,2,natural,cash_in,200.00,EUR
                            2016-01-06,1,natural,cash_out,30000,JPY
                            2016-01-07,1,natural,cash_out,1000.00,EUR
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

        $commissionFee = $commissionFeeList[2];

        $this->assertSame(
            0.7,
            $commissionFee
        );
    }

    public function testCalculateDifferentCurrencySingleUserDifferentOperationType()
    {
        $defaultCurrency = new Currency('EUR');

        $inputFileContent = <<<HERDOC
                            2016-01-05,1,natural,cash_in,200.00,EUR
                            2016-01-06,1,natural,cash_out,30000,JPY
                            2016-01-07,1,natural,cash_out,1000.00,EUR
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

        $commissionFee = $commissionFeeList[2];

        $this->assertSame(
            0.7,
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
