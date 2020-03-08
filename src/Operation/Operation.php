<?php
/**
 * Created by PhpStorm.
 * User: saulius
 * Date: 20.3.5
 * Time: 21.30
 */

namespace CommissionFee\Operation;

use CommissionFee\Currency;

// @ToDo: move date to operation. Date might be needed to track the operation and it's currency rates.
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

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function setCurrency(Currency $currency): void
    {
        $this->currency = $currency;
    }

    public function getOperationType(): OperationType
    {
        return $this->operationType;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
