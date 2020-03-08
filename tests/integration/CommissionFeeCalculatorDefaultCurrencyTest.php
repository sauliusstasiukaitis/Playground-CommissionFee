<?php

namespace CommissionFeeTest\Integration;

use CommissionFee\CommissionFeeCalculator;
use CommissionFee\CommissionFeeContextFactory;
use CommissionFee\CommisssionFeeCalculateStrategy\StrategyFactory;
use CommissionFee\Currency;
use bovigo\vfs\vfsStream;
use CommissionFee\CurrencyConverter;
use CommissionFee\ParametersToObjectsFactory;
use CommissionFee\Storage\PrivateCacheOutStrategyDataRepository;
use PHPUnit\Framework\TestCase;

// @ToDo: add more tests to cover every case.
// So it would be clear from test that cache in is failing for a company without looking to acceptance test failure.
class CommissionFeeCalculatorDefaultCurrencyTest extends TestCase
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
            0.6,
            $commissionFee,
            "Commissions for cash out after first 1000 - 0.3% therefore for 1200 should be 0.6"
        );
    }

    public function testCalculateCashOutPrivateWhenAboveLimitFromTheFirstTransaction()
    {
        $defaultCurrency = new Currency('EUR');

        $inputFileContent = <<<HERDOC
                            2014-12-31,4,natural,cash_out,1200.00,EUR
                            2015-01-01,4,natural,cash_out,1000.00,EUR
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
            3.00,
            $commissionFee,
            "Commissions for cash out - 0.3% therefore for 1000 should be 3"
        );
    }

    public function testCalculateCashOutPrivateWhenBelowLimit()
    {
        $defaultCurrency = new Currency('EUR');

        $inputFileContent = <<<HERDOC
                            2014-12-31,4,natural,cash_out,200.00,EUR
                            2014-12-31,4,natural,cash_out,300.00,EUR
                            2015-01-01,4,natural,cash_out,400.00,EUR
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
            0.00,
            $commissionFee,
            "Commissions for cash out should be 0 as below threshold."
        );
    }

    public function testCalculateCashOutPrivateWhenAboveLimitFromSeveralTransactions()
    {
        $defaultCurrency = new Currency('EUR');

        $inputFileContent = <<<HERDOC
                            2014-12-31,4,natural,cash_out,500.00,EUR
                            2014-12-31,4,natural,cash_out,700.00,EUR
                            2015-01-01,4,natural,cash_out,1000.00,EUR
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
            3.00,
            $commissionFee,
            "Commissions for cash out - 0.3% therefore for 1000 should be 3"
        );
    }

    public function testCalculateCashOutPrivateWhenAboveLimitAfter4thTransaction()
    {
        $defaultCurrency = new Currency('EUR');

        $inputFileContent = <<<HERDOC
                            2014-12-31,4,natural,cash_out,100.00,EUR
                            2014-12-31,4,natural,cash_out,100.00,EUR
                            2014-12-31,4,natural,cash_out,100.00,EUR
                            2015-01-01,4,natural,cash_out,1000.00,EUR
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

        $commissionFee = $commissionFeeList[3];

        $this->assertSame(
            3.00,
            $commissionFee,
            "Commissions for cash out - 0.3% therefore for 1000 should be 3"
        );
    }

    public function testCalculateCashOutPrivateWhenBelowLimitAsForDifferentWeeks()
    {
        $defaultCurrency = new Currency('EUR');

        $inputFileContent = <<<HERDOC
                            2014-12-31,4,natural,cash_out,1200.00,EUR
                            2015-01-01,4,natural,cash_out,1000.00,EUR
                            2016-01-05,4,natural,cash_out,1000.00,EUR
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
            0.00,
            $commissionFee,
            "Should be 0 as third transaction is in a new week."
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
