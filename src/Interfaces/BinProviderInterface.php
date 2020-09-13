<?php

namespace Matasar\CommissionCalculator\Interfaces;

use Matasar\CommissionCalculator\Entities\BinData;

interface BinProviderInterface
{
    public function getBinData(int $bin): BinData;
}
