<?php
/**
 * Created by PhpStorm.
 * User: saulius
 * Date: 20.3.5
 * Time: 19.10
 */

namespace CommissionFee;

require_once '../../vendor/autoload.php';

$defaultCurrency = new Currency('EUR');

// @ToDo: extract from file.
$currency = new Currency('EUR');
$amount = 1200.00;
$dateInput = '2014-12-31';
$userIdInput = 4;
$userTypeInput = 'natural';
$operationTypeInput = 'cash_out';

// @ToDo: extract to a class which would load needed objects from a strings.
$date = strtotime($dateInput);
$user = new User($userIdInput, $userTypeInput);

$operationTypeFactory = new OperationTypeFactory();
$operationType = $operationTypeFactory->build($operationTypeInput);

$operation = new Operation(
    $operationType,
    $amount,
    $currency
);

$commitionFeeCalculator = new CommitionFeeCalculatorContext(
    $date,
    $user,
    $operation,
    $defaultCurrency
);
$commissionFee = $commitionFeeCalculator->calculate();
