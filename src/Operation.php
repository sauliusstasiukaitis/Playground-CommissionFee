<?php
/**
 * Created by PhpStorm.
 * User: saulius
 * Date: 20.3.5
 * Time: 21.30
 */

namespace CommissionFee;


class Operation
{
    private OperationType $operationType;
    private float $amount;
    private Currency $currency;

    /**
     * @param OperationType $operationType
     * @param float $amount
     * @param Currency $currency
     */
    public function __construct(
        OperationType $operationType,
        float $amount,
        Currency $currency
    ) {
        $this->operationType = $operationType;
        $this->currency = $currency;
        $this->amount = $amount;
    }

    /**
     * @return OperationType
     */
    public function getOperationType(): OperationType
    {
        return $this->operationType;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
