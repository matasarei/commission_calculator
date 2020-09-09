<?php

namespace Matasar\CommissionCalculator;

use Matasar\CommissionCalculator\Interfaces\BinProviderInterface;
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

    public function __construct(
        BinProviderInterface $binProvider,
        CurrencyRatesProviderInterface $currencyRatesProvider
    ) {
        $this->binProvider = $binProvider;
        $this->currencyRatesProvider = $currencyRatesProvider;
    }

    /**
     * @param int $bin
     * @param float $amount
     * @param string $currencyCode
     *
     * @return float|int
     */
    public function calculateCommission(int $bin, float $amount, string $currencyCode)
    {
        $countryCode = $this->binProvider->getCountryIsoByBin($bin);
        $isEuCountry = EuCountries::isEuCountry($countryCode);

        if ('EUR' !== $currencyCode) {
            $exchangeRate = $this->currencyRatesProvider->getExchangeRate($currencyCode);
            $amount = $amount / $exchangeRate;
        }

        return $amount * ($isEuCountry ? 0.01 : 0.02);
    }
}
