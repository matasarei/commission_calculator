<?php

namespace Matasar\CommissionCalculator\CalculationStrategies;

class CalculateWithCeilingStrategy extends DefaultCalculationStrategy
{
    /**
     * @var int
     */
    protected $precision;

    public function __construct(int $precision = 2)
    {
        $this->precision = $precision;
    }

    public function calculateCommission(float $amount, string $currencyCode, string $countryCode): float
    {
        $value = parent::calculateCommission($amount, $currencyCode, $countryCode);
        $precision = pow(10, $this->precision);

        return ceil($value * $precision) / $precision;
    }
}
