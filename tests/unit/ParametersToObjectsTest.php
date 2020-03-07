<?php

namespace CommissionFeeTest\Unit;

use CommissionFee\Operation\OperationTypeCashIn;
use CommissionFee\Operation\OperationTypeCashOut;
use CommissionFee\ParametersToObjects;
use CommissionFee\Currency;
use CommissionFee\Operation\Operation;
use CommissionFee\User;
use PHPUnit\Framework\TestCase;

class ParametersToObjectsTest extends TestCase
{
    public function providerGetDate()
    {
        return [
            ['2015-01-01,1,natural,cash_out,1200.00,EUR', strtotime('2015-01-01')],
            ['2016-02-02,1,natural,cash_out,1200.00,EUR', strtotime('2016-02-02')],
        ];
    }

    /**
     * @param string $inputLine
     *
     * @dataProvider providerGetDate
     */
    public function testGetDate(string $inputLine, int $dateTimestamp)
    {
        $parametersToObjects = new ParametersToObjects($inputLine);

        $this->assertSame($parametersToObjects->getDate(), $dateTimestamp);
    }

    public function testGePrivateUser()
    {
        $inputLine = '2015-01-01,1,natural,cash_out,1200.00,EUR';
        $parametersToObjects = new ParametersToObjects($inputLine);

        $expectedUser = new User(1, 'natural');

        $this->assertEquals($parametersToObjects->getUser(), $expectedUser);
    }

    public function testGeCompanyUser()
    {
        $inputLine = '2015-01-01,1,legal,cash_out,1200.00,EUR';
        $parametersToObjects = new ParametersToObjects($inputLine);

        $expectedUser = new User(1, 'legal');

        $this->assertEquals($parametersToObjects->getUser(), $expectedUser);
    }

    public function testGetOperationCashOut()
    {
        $inputLine = '2015-01-01,1,legal,cash_out,1200.00,EUR';
        $parametersToObjects = new ParametersToObjects($inputLine);

        $expectedOperation = new Operation(
            new OperationTypeCashOut(),
            '1200.00',
            new Currency('EUR')
        );

        $this->assertEquals($parametersToObjects->getOperation(), $expectedOperation);
    }

    public function testGetOperationCashIn()
    {
        $inputLine = '2015-01-01,1,legal,cash_in,1200.00,EUR';
        $parametersToObjects = new ParametersToObjects($inputLine);

        $expectedOperation = new Operation(
            new OperationTypeCashIn(),
            '1200.00',
            new Currency('EUR')
        );

        $this->assertEquals($parametersToObjects->getOperation(), $expectedOperation);
    }
}
