<?php

namespace CommissionFee;

use CommissionFee\CommisssionFeeCalculateStrategy\StrategyFactory;
use CommissionFee\Currency\Currency;
use CommissionFee\Currency\CurrencyConverter;
use CommissionFee\Storage\PrivateCacheOutStrategyDataRepository;

class CommissionFeeCalculator
{
    private ParametersToObjectsFactory $parametersToObjectsBuilder;
    private CommissionFeeContextFactory $commissionFeeContextBuilder;
    private StrategyFactory $strategyFactory;
    private Currency $defaultCurrency;
    private PrivateCacheOutStrategyDataRepository $customerData;
    private CurrencyConverter $currencyConverter;

    public function __construct(
        ParametersToObjectsFactory $parametersToObjectsBuilder,
        CommissionFeeContextFactory $commissionFeeContextBuilder,
        StrategyFactory $strategyFactory,
        Currency $defaultCurrency,
        PrivateCacheOutStrategyDataRepository $customerData,
        CurrencyConverter $currencyConverter
    ) {
        $this->parametersToObjectsBuilder = $parametersToObjectsBuilder;
        $this->commissionFeeContextBuilder = $commissionFeeContextBuilder;
        $this->strategyFactory = $strategyFactory;
        $this->defaultCurrency = $defaultCurrency;
        $this->customerData = $customerData;
        $this->currencyConverter = $currencyConverter;
    }

    /**
     * @param resource $inputFile resource to get content from input file.
     * @return array list of commission fees.
     *
     * @ToDo: implement convertion rates.
     *
     * @throws \Exception
     */
    public function calculate($inputFile)
    {
        $commissionFeeList = [];

        while (!feof($inputFile)) {
            $line = trim(fgets($inputFile));

            if (empty($line)) {
                continue;
            }

            $parametersToObject = $this->parametersToObjectsBuilder->create($line);

            $operation = $parametersToObject->getOperation();

            $operationCurrencyBeforeConversion = $operation->getCurrency()->getAbbreviation();

            $originalCurrency = null;
            if ($operationCurrencyBeforeConversion !== $this->defaultCurrency->getAbbreviation()) {
                $originalCurrency = clone $operation->getCurrency();
                $operation = $this->currencyConverter->convertOperation($operation, $this->defaultCurrency);
            }

            $context = $this->commissionFeeContextBuilder->create(
                $parametersToObject->getDate(),
                $parametersToObject->getCustomer(),
                $operation,
                $this->customerData
            );

            $strategy = $this->strategyFactory->create($context);

            $commissionFee = $strategy->calculate($context->getOperation());

            if (!is_null($originalCurrency)) {
                $commissionFee = $this->currencyConverter->convertAmount(
                    $commissionFee,
                    $operation->getCurrency(),
                    $originalCurrency
                );
            }

            $commissionFeeList[] = round(
                $commissionFee,
                2,
                PHP_ROUND_HALF_UP
            );
        }
        fclose($inputFile);

        return $commissionFeeList;
    }
}
