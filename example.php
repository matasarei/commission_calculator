<?php

require_once 'vendor/autoload.php';

use GuzzleHttp\Client as HttpClient;
use Matasar\CommissionCalculator\BinProviders\BinListNetProvider;
use Matasar\CommissionCalculator\CommissionCalculator;
use Matasar\CommissionCalculator\ExchangeRatesProviders\ExchangeRatesApiProvider;

$fileName = $argv[1] ?? 'example.txt';

if (!is_readable($fileName)) {
    echo sprintf("File '%s' doesn't exist or not readable\n", $fileName);

    exit(1);
}

$httpClient = new HttpClient();
$binProvider = new BinListNetProvider($httpClient);
$exchangeProvider = new ExchangeRatesApiProvider($httpClient);

$calculator = new CommissionCalculator($binProvider, $exchangeProvider);

$rows = explode("\n", file_get_contents($fileName));

foreach ($rows as $row) {
    $data = json_decode($row, true);

    if (empty($data)) {
        echo "An empty row or corrupted data! \n";

        continue;
    }

    $commission = $calculator->calculateCommission($data['bin'], $data['amount'], $data['currency']);

    echo round($commission, 2) . "\n";
}
