<?php

/**
 * Created by PhpStorm.
 * User: saulius
 * Date: 20.3.5
 * Time: 20.33
 */
namespace CommissionFee;

interface ExchangeRateInterface
{
    public function get(CurrencyInterface $currencyFrom, CurrencyInterface $currencyTo): float;
}
