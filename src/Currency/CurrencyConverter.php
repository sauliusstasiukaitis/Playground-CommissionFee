<?php
/**
 * Created by PhpStorm.
 * User: saulius
 * Date: 20.3.5
 * Time: 20.12
 */

namespace CommissionFee\Currency;

use CommissionFee\Operation\Operation;

/**
 * Converts currencies by in class predefined number.
 * Real converter should use a 3rd party to get and store all rates by the needed dates.
 *
 */
class CurrencyConverter
{
    /**
     * @param Operation $operation
     * @param Currency $convertToCurrency
     *
     * @return Operation new object with updated amount and currency.
     *
     * @throws \Exception for unknown currency conversion.
     */
    public function convertOperation(Operation $operation, Currency $convertToCurrency): Operation
    {
        $operationCurrency = $operation->getCurrency()->getAbbreviation();

        $currencyRate = $this->getCurrencyRate($operationCurrency, $convertToCurrency->getAbbreviation());

        $convertedAmount = round(
            $operation->getAmount() * $currencyRate,
            2
        );

        $convertedOperation = clone $operation;
        $convertedOperation->setAmount($convertedAmount);
        $convertedOperation->setCurrency($convertToCurrency);

        return $convertedOperation;
    }

    public function convertAmount(float $amountToConvert, Currency $operationCurrency, Currency $convertToCurrency): float
    {
        $operationCurrencyAbreviation = $operationCurrency->getAbbreviation();

        $currencyRate = $this->getCurrencyRate($operationCurrencyAbreviation, $convertToCurrency->getAbbreviation());

        return $amountToConvert * $currencyRate;
    }

    private function getEurToJpy(): float
    {
        return 129.53;
    }

    private function getJpyToEur(): float
    {
        return 1 / $this->getEurToJpy();
    }

    private function getEurToUsd(): float
    {
        return 1.1497;
    }

    private function getUsdToEur(): float
    {
        return 1 / $this->getEurToUsd();
    }

    /**
     * @param string $operationCurrency
     * @param string $convertToCurrency
     *
     * @return float
     *
     * @throws \Exception for unknown currency conversion.
     */
    private function getCurrencyRate(string $operationCurrency, string $convertToCurrency): float
    {
        if ($operationCurrency === 'JPY' && $convertToCurrency === 'EUR') {
            return $this->getJpyToEur();
        }

        if ($operationCurrency === 'EUR' && $convertToCurrency === 'JPY') {
            return $this->getEurToJpy();
        }
        if ($operationCurrency === 'USD' && $convertToCurrency === 'EUR') {
            return $this->getUsdToEur();
        }

        if ($operationCurrency === 'EUR' && $convertToCurrency === 'USD') {
            return $this->getEurToUsd();
        }

        throw new \Exception("Unknown currency convertion: $operationCurrency to $convertToCurrency");
    }
}
