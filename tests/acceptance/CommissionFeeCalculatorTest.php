<?php

namespace CommissionFeeTest;

use CommissionFee\CommissionFeeContext;

class CommissionFeeCalculatorTest extends \PHPUnit\Framework\TestCase
{
    const INPUT_FILE_PATH = __DIR__ . '/fixtures/input.csv';

    const EXPECTED_RESULTS = <<<HERDOC
                                0.60
                                3.00
                                0.00
                                0.06
                                0.90
                                0
                                0.70
                                0.30
                                0.30
                                5.00
                                0.00
                                0.00
                                8612
                                HERDOC;

    public function testCalculateCorrectFromFile()
    {
        //
    }
}
