<?php
/**
 * Created by PhpStorm.
 * User: saulius
 * Date: 20.3.5
 * Time: 21.19
 */

namespace CommissionFee;


class OperationTypeFactory
{
    const CASH_OUT = 'cash_out';
    const CASH_IN = 'cash_in';

    public function build(string $type): OperationType
    {
        switch ($type) {
            case self::CASH_OUT:
                $operationType = new OperationTypeCashOut();
                break;

            case self::CASH_IN:
                $operationType = new OperationTypeCashIn();
                break;
        }

        return $operationType;
    }
}
