<?php

namespace Matasar\CommissionCalculator\CalculationStrategies;

use Matasar\CommissionCalculator\EuCountries;
use Matasar\CommissionCalculator\Interfaces\CalculationStrategyInterface;

class DefaultCalculationStrategy implements CalculationStrategyInterface
{
    protected const COMMISSION_EU = 0.01;
    protected const COMMISSION_OTHER = 0.02;

    /**
     * @param float $amount
     * @param string $currencyCode
     * @param string $countryCode
     *
     * @return float
     */
    public function calculateCommission(float $amount, string $currencyCode, string $countryCode): float
    {
        $isEuCountry = EuCountries::isEuCountry($countryCode);

        return $amount * ($isEuCountry ? self::COMMISSION_EU : self::COMMISSION_OTHER);
    }
}
