<?php

namespace Matasar\CommissionCalculator\Entities;

class CurrencyExchangeRate
{
    /**
     * @var string
     */
    protected $currencyIso;

    /**`
     * @var float
     */
    protected $rate;

    public function __construct(string $currencyIso, float $rate)
    {
        $this->currencyIso = $currencyIso;
        $this->rate = $rate;
    }

    public function getCurrencyIso(): string
    {
        return $this->currencyIso;
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}
