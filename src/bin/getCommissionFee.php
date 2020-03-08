<?php
/**
 * Created by PhpStorm.
 * User: saulius
 * Date: 20.3.5
 * Time: 19.10
 */

require_once __DIR__. '/../../vendor/autoload.php';

if (!isset($argv[1])) {
    throw new \Exception('Pass input file path as a parameter!');
}

$inputFilePath = $argv[1];

if (!is_file($inputFilePath)) {
    throw new \Exception('Input file path parameter leads nowhere...');
}

$fileStream = fopen($inputFilePath, 'r');

if ($fileStream === false) {
    throw new \Exception('Not possible to open file for read!');
}

$commissionFeeCalculator = new \CommissionFee\CommissionFeeCalculator(
    new \CommissionFee\ParametersToObjectsFactory(),
    new \CommissionFee\CommissionFeeContextFactory(),
    new \CommissionFee\CommisssionFeeCalculateStrategy\StrategyFactory(),
    new \CommissionFee\Currency\Currency('EUR'),
    new \CommissionFee\Storage\PrivateCacheOutStrategyDataRepository(),
    new \CommissionFee\Currency\CurrencyConverter()
);
$commissionFeeList = $commissionFeeCalculator->calculate($fileStream);

echo implode(PHP_EOL, $commissionFeeList);
