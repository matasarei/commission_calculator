<?php

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Response as HttpResponse;
use Matasar\CommissionCalculator\BinProviders\BinListNetProvider;
use Matasar\CommissionCalculator\CommissionCalculator;
use Matasar\CommissionCalculator\ExchangeRatesProviders\ExchangeRatesApiProvider;
use PHPUnit\Framework\TestCase;

final class CommissionCalculatorTest extends TestCase
{
    private $calculator;

    /**
     * @dataProvider inputDataProvider
     *
     * @param int $bin
     * @param float $amount
     * @param string $currencyCode
     * @param float $expected
     */
    public function testCommissionCalculator(int $bin, float $amount, string $currencyCode, float $expected)
    {
        $commission = $this->calculator->calculateCommission($bin, $amount, $currencyCode);

        $this->assertEquals($expected, $commission);
    }

    /**
     * @return array[]
     */
    public function inputDataProvider()
    {
        return [
            [
                45717360,
                100.00,
                'EUR',
                1
            ],
            [
                516793,
                50.00,
                'USD',
                0.42470058608681
            ],
            [
                45417360,
                10000.00,
                'JPY',
                1.5993602558976
            ],
            [
                41417360,
                130.00,
                'USD',
                2.2084430476514
            ],
            [
                4745030,
                2000.00,
                'GBP',
                43.853398090185
            ]
        ];
    }

    protected function setUp(): void
    {
        $binProvider = new BinListNetProvider(new HttpClient());

        $exchangeRates = json_encode([
            'rates' => [
                'USD' => 1.1773,
                'GBP' => 0.91213,
                'JPY' => 125.05
            ]
        ]);

        $fakeHttpClient = $this->createMock(HttpClient::class);
        $fakeHttpClient->method('request')
            ->willReturn(new HttpResponse(200, [], $exchangeRates));

        $exchangeProvider = new ExchangeRatesApiProvider($fakeHttpClient);

        $this->calculator = new CommissionCalculator($binProvider, $exchangeProvider);
    }
}
