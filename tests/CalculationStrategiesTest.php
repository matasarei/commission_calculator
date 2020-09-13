<?php

use Matasar\CommissionCalculator\CalculationStrategies\CalculateWithCeilingStrategy;
use Matasar\CommissionCalculator\CalculationStrategies\DefaultCalculationStrategy;
use PHPUnit\Framework\TestCase;

final class CalculationStrategiesTest extends TestCase
{
    private const USD_RATE = 1.1773;
    private const JPY_RATE = 125.05;

    public function testCalculateWithCeilingStrategy()
    {
        $strategy = new CalculateWithCeilingStrategy(2);

        $this->assertEquals(
            $strategy->calculateCommission(50 / self::USD_RATE, 'USD', 'LT'),
            0.43 // 0.42470058609
        );

        $strategy = new CalculateWithCeilingStrategy(1);

        $this->assertEquals(
            $strategy->calculateCommission(130 / self::USD_RATE, 'USD', 'US'),
            2.3 // 2.20844304766
        );
    }

    /**
     * @dataProvider defaultStrategyDataProvider
     *
     * @param float $amount
     * @param string $currencyCode
     * @param string $countryCode
     * @param float $actual
     */
    public function testDefaultStrategy(float $amount, string $currencyCode, string $countryCode, float $actual)
    {
        $strategy = new DefaultCalculationStrategy();

        $this->assertEquals(
            $strategy->calculateCommission($amount, $currencyCode, $countryCode),
            $actual
        );
    }

    /**
     * @return array[]
     */
    public function defaultStrategyDataProvider()
    {
        return [
            [
                100,
                'EUR',
                'DK',
                1
            ],
            [
                50 / self::USD_RATE,
                'USD',
                'LT',
                0.42470058609
            ],
            [
                10000 / self::JPY_RATE,
                'JPY',
                'JP',
                1.5993602559000002
            ],
            [
                130 / self::USD_RATE,
                'USD',
                'US',
                2.20844304766
            ],
        ];
    }
}
