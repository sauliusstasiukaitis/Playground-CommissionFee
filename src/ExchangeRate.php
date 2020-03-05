<?php

/**
 * Created by PhpStorm.
 * User: saulius
 * Date: 20.3.5
 * Time: 20.32
 */
namespace CommissionFee;

class ExchangeRate implements ExchangeRateInterface
{

    /**
     * ExchangeRate constructor.
     */
    public function __construct()
    {
    }

    public function get(CurrencyInterface $currencyFrom, CurrencyInterface $currencyTo): float
    {
        
    }
}
