<?php

namespace Matasar\CommissionCalculator\Interfaces;

interface CurrencyRatesProviderInterface
{
    public function getExchangeRate($currencyIso);
}
