<?php

namespace Matasar\CommissionCalculator\Interfaces;

interface BinProviderInterface
{
    public function getCountryIsoByBin(int $bin): string;
}
