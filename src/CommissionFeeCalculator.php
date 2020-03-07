<?php

namespace CommissionFee;

use CommissionFee\CommisssionFeeCalculateStrategy\StrategyFactory;

class CommissionFeeCalculator
{
    private ParametersToObjectsFactory $parametersToObjectsBuilder;
    private CommissionFeeContextFactory $commissionFeeContextBuilder;
    private StrategyFactory $strategyFactory;
    private Currency $defaultCurrency;

    public function __construct(
        ParametersToObjectsFactory $parametersToObjectsBuilder,
        CommissionFeeContextFactory $commissionFeeContextBuilder,
        StrategyFactory $strategyFactory,
        Currency $defaultCurrency
    ) {
        $this->parametersToObjectsBuilder = $parametersToObjectsBuilder;
        $this->commissionFeeContextBuilder = $commissionFeeContextBuilder;
        $this->strategyFactory = $strategyFactory;
        $this->defaultCurrency = $defaultCurrency;
    }

    /**
     * @param resource $inputFile resource to get content from input file.
     * @return array list of commission fees.
     *
     * @ToDo: implement convertion rates.
     */
    public function calculate($inputFile)
    {
        $commissionFeeList = [];

        while (!feof($inputFile)) {
            $line = fgets($inputFile);

            $parametersToObject = $this->parametersToObjectsBuilder->create($line);

            $context = $this->commissionFeeContextBuilder->create(
                $parametersToObject->getDate(),
                $parametersToObject->getUser(),
                $parametersToObject->getOperation()
            );

            $strategy = $this->strategyFactory->create($context);

            $commissionFeeList[] = $strategy->calculate($context->getOperation());
        }
        fclose($inputFile);

        return $commissionFeeList;
    }
}
