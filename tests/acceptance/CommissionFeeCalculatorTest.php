<?php

namespace CommissionFeeTest\Acceptance;

use PHPUnit\Framework\TestCase;

class CommissionFeeCalculatorTest extends TestCase
{
    const INPUT_FILE_PATH = __DIR__ . '/fixtures/input.csv';

    const EXPECTED_RESULTS = [
                                '0.6',
                                '3',
                                '0',
                                '0.06',
                                '0.9',
                                '0',
                                '0.7',
                                '0.3',
                                '0.3',
                                '5',
                                '0',
                                '0',
                                '8612',
    ];

    /*
     * Testing if
     * 1. getCommissionFee file acts correct:
     *       - Read from the input file.
     *       - Print results back to stdOut.
     * 2. Entire system works with requested data.
     *
     * Not the best approach to call exec but fits for the task.
     */
    public function testCalculateCorrectFromFile()
    {
        $output = [];
        $inputFilePath = __DIR__ . '/fixtures/input.csv';
        exec("php ../../src/bin/getCommissionFee.php $inputFilePath", $output);

        $this->assertEquals(self::EXPECTED_RESULTS, $output);
    }
}
