<?php

require_once 'vendor/autoload.php';

use GuzzleHttp\Client as HttpClient;
use Matasar\CommissionCalculator\BinProviders\BinListNetProvider;
use Matasar\CommissionCalculator\CalculationStrategies\CalculateWithCeilingStrategy;
use Matasar\CommissionCalculator\CommissionCalculator;
use Matasar\CommissionCalculator\Entities\Transaction;
use Matasar\CommissionCalculator\ExchangeRatesProviders\ExchangeRatesApiProvider;

$fileName = $argv[1] ?? 'example.txt';

if (!is_readable($fileName)) {
    echo sprintf("File '%s' doesn't exist or not readable\n", $fileName);

    exit(1);
}

$httpClient = new HttpClient();
$binProvider = new BinListNetProvider($httpClient);
$exchangeProvider = new ExchangeRatesApiProvider($httpClient);
$calculationStrategy = new CalculateWithCeilingStrategy();

$calculator = new CommissionCalculator($binProvider, $exchangeProvider, $calculationStrategy);

$rows = explode("\n", file_get_contents($fileName));

foreach ($rows as $row) {
    $data = json_decode($row, true);

    if (!isset($data['bin'], $data['amount'], $data['currency'])) {
        echo "An empty row or corrupted data! \n";

        continue;
    }

    try {
        $commission = $calculator->calculateCommission(new Transaction($data['bin'], $data['amount'], $data['currency']));
    } catch (Throwable $ex) {
        echo "An unexpected error occurred! It may happen due network or an external service issues. Please try again later.";

        exit(1);
    }

    echo $commission . "\n";
}
