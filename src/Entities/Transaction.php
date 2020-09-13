<?php

namespace Matasar\CommissionCalculator\Entities;

class Transaction
{
    /**
     * @var int
     */
    protected $bin;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var string
     */
    protected $currencyCode;

    public function __construct(int $bin, float $amount, string $currencyCode)
    {
        $this->bin = $bin;
        $this->amount = $amount;
        $this->currencyCode = $currencyCode;
    }

    public function getBin(): int
    {
        return $this->bin;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }
}
