<?php

/**
 * Created by PhpStorm.
 * User: saulius
 * Date: 20.3.5
 * Time: 20.34
 */

namespace CommissionFee;

class Currency
{
    private string $abbreviation;

    /**
     * Currency constructor.
     * @param string $abbreviation
     *
     * @ToDo: extract to factory which could create fully functional currency object by their abbreviations.
     */
    public function __construct(string $abbreviation)
    {
        $this->abbreviation = $abbreviation;
    }

    public function getAbbreviation(): string
    {
        return $this->abbreviation;
    }
}
