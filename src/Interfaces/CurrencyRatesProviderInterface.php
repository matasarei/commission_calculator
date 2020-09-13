<?php

namespace Matasar\CommissionCalculator\Interfaces;

use Matasar\CommissionCalculator\Entities\CurrencyExchangeRate;

interface CurrencyRatesProviderInterface
{
    public function getExchangeRate($currencyIso): CurrencyExchangeRate;
}
