<?php

namespace CommissionFee;

use CommissionFee\Operation\Operation;
use CommissionFee\Operation\OperationTypeFactory;

class ParametersToObjects
{
    const PARAMETERS_DELIMITER = ',';

    private string $inputLine;

    public function __construct(string $inputLine)
    {
        $this->inputLine = $inputLine;
    }

    /**
     * @return int
     *
     * @throws \Exception
     */
    public function getDate(): int
    {
        $parameters = explode(static::PARAMETERS_DELIMITER, $this->inputLine);
        $date = $parameters[0];

        $timestamp = strtotime($date);

        if ($timestamp === false) {
            throw new \Exception('Input Date could not be converted to a timestamp: '. $date);
        }

        return $timestamp;
    }

    public function getCustomer(): Customer
    {
        $parameters = explode(static::PARAMETERS_DELIMITER, $this->inputLine);
        $customerId = $parameters[1];
        $customerType = $parameters[2];

        return new Customer($customerId, $customerType);
    }

    public function getOperation(): Operation
    {
        $parameters = explode(static::PARAMETERS_DELIMITER, $this->inputLine);
        $operationTypeInput = $parameters[3];
        $amount = $parameters[4];
        $currencyInput = $parameters[5];

        $operationTypeFactory = new OperationTypeFactory();
        $operationType = $operationTypeFactory->build($operationTypeInput);

        return new Operation(
                $operationType,
                $amount,
                new Currency($currencyInput)
            );
        
    }
}
