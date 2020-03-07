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

    public function getDate()
    {
        $parameters = explode(static::PARAMETERS_DELIMITER, $this->inputLine);
        $date = $parameters[0];

        return strtotime($date);
    }

    public function getUser()
    {
        $parameters = explode(static::PARAMETERS_DELIMITER, $this->inputLine);
        $userId = $parameters[1];
        $userType = $parameters[2];

        return new User($userId, $userType);
    }

    public function getOperation()
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
