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

    /**
     * Operation constructor.
     * @param OperationType $operationType
     * @param float $param
     * @param Currency $param1
     */
    public function __construct(
        OperationType $operationType,
        float $param,
        Currency $param1
    ) {
    }
}
