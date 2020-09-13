<?php

namespace Matasar\CommissionCalculator\Interfaces;

interface CalculationStrategyInterface
{
    public function calculateCommission(float $amount, string $currencyCode, string $countryCode): float;
}
