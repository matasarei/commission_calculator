<?php

namespace Matasar\CommissionCalculator\Entities;

class BinData
{
    /**
     * @var int
     */
    protected $bin;

    /**
     * @var string
     */
    protected $countryCode;

    public function __construct(int $bin, string $countryCode)
    {
        $this->bin = $bin;
        $this->countryCode = $countryCode;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }
}
