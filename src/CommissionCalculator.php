<?php

namespace Matasar\CommissionCalculator;

use Matasar\CommissionCalculator\Entities\Transaction;
use Matasar\CommissionCalculator\Interfaces\BinProviderInterface;
use Matasar\CommissionCalculator\Interfaces\CalculationStrategyInterface;
use Matasar\CommissionCalculator\Interfaces\CurrencyRatesProviderInterface;

class CommissionCalculator
{
    /**
     * @var BinProviderInterface
     */
    protected $binProvider;

    /**
     * @var CurrencyRatesProviderInterface
     */
    protected $currencyRatesProvider;

    /**
     * @var CalculationStrategyInterface
     */
    protected $calculationStrategy;

    public function __construct(
        BinProviderInterface $binProvider,
        CurrencyRatesProviderInterface $currencyRatesProvider,
        CalculationStrategyInterface $calculationStrategy
    ) {
        $this->binProvider = $binProvider;
        $this->currencyRatesProvider = $currencyRatesProvider;
        $this->calculationStrategy = $calculationStrategy;
    }

    /**
     * @param Transaction $transaction
     *
     * @return float|int
     */
    public function calculateCommission(Transaction $transaction)
    {
        $binData = $this->binProvider->getBinData($transaction->getBin());

        $amount = $transaction->getAmount();
        $currencyCode = $transaction->getCurrencyCode();
        $countryCode = $binData->getCountryCode();

        if ('EUR' !== $currencyCode) {
            $exchangeRate = $this->currencyRatesProvider->getExchangeRate($currencyCode);
            $amount /= $exchangeRate->getRate();
        }

        return $this->calculationStrategy->calculateCommission($amount, $currencyCode, $countryCode);
    }
}
