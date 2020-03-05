<?php
/**
 * Created by PhpStorm.
 * User: saulius
 * Date: 20.3.5
 * Time: 20.38
 */

namespace CommissionFee;

interface CurrencyInterface
{
    public function getAbbreviation(): string;
}
