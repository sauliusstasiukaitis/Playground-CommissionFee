<?php

namespace CommissionFee;

use CommissionFee\CommisssionFeeCalculateStrategy\StrategyFactory;
use CommissionFee\Storage\PrivateCacheOutStrategyDataRepository;

class CommissionFeeCalculator
{
    private ParametersToObjectsFactory $parametersToObjectsBuilder;
    private CommissionFeeContextFactory $commissionFeeContextBuilder;
    private StrategyFactory $strategyFactory;
    private Currency $defaultCurrency;
    private PrivateCacheOutStrategyDataRepository $userData;

    public function __construct(
        ParametersToObjectsFactory $parametersToObjectsBuilder,
        CommissionFeeContextFactory $commissionFeeContextBuilder,
        StrategyFactory $strategyFactory,
        Currency $defaultCurrency,
        PrivateCacheOutStrategyDataRepository $userData
    ) {
        $this->parametersToObjectsBuilder = $parametersToObjectsBuilder;
        $this->commissionFeeContextBuilder = $commissionFeeContextBuilder;
        $this->strategyFactory = $strategyFactory;
        $this->defaultCurrency = $defaultCurrency;
        $this->userData = $userData;
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

            $context = $this->commissionFeeContextBuilder->create(
                $parametersToObject->getDate(),
                $parametersToObject->getUser(),
                $parametersToObject->getOperation(),
                $this->userData
            );

            $strategy = $this->strategyFactory->create($context);

            $commissionFeeList[] = $strategy->calculate($context->getOperation());
        }
        fclose($inputFile);

        return $commissionFeeList;
    }
}
