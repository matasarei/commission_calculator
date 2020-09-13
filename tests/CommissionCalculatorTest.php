<?php

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request as HttpRequest;
use GuzzleHttp\Psr7\Response as HttpResponse;
use Matasar\CommissionCalculator\BinProviders\BinListNetProvider;
use Matasar\CommissionCalculator\CalculationStrategies\DefaultCalculationStrategy;
use Matasar\CommissionCalculator\CommissionCalculator;
use Matasar\CommissionCalculator\Entities\Transaction;
use Matasar\CommissionCalculator\ExchangeRatesProviders\ExchangeRatesApiProvider;
use PHPUnit\Framework\TestCase;

final class CommissionCalculatorTest extends TestCase
{
    private const BIN_MAP = [
        45717360 => 'DK',
        516793 => 'LT',
        45417360 => 'JP',
        41417360 => 'US',
        4745030 => 'GB'
    ];

    private $calculator;

    /**
     * @dataProvider inputDataProvider
     *
     * @param Transaction $transaction
     * @param float $expected
     */
    public function testCommissionCalculator(Transaction $transaction, float $expected)
    {
        $commission = $this->calculator->calculateCommission($transaction);

        $this->assertEquals($expected, $commission);
    }

    /**
     * @return array[]
     */
    public function inputDataProvider()
    {
        return [
            [
                new Transaction(45717360, 100.00, 'EUR'),
                1
            ],
            [
                new Transaction(516793, 50.00, 'USD'),
                0.42470058608681
            ],
            [
                new Transaction(45417360, 10000.00, 'JPY'),
                1.5993602558976
            ],
            [
                new Transaction(41417360, 130.00, 'USD'),
                2.2084430476514
            ],
            [
                new Transaction(4745030, 2000.00, 'GBP'),
                43.853398090185
            ]
        ];
    }

    protected function setUp(): void
    {
        $this->calculator = new CommissionCalculator(
            $this->getBinProvider(),
            $this->getExchangeProvider(),
            new DefaultCalculationStrategy()
        );
    }

    protected function getBinProvider(): BinListNetProvider
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient->method('request')
            ->willReturnCallback(function ($method, $url) {
                if (preg_match('/\d+$/', $url, $matches)) {
                    return new HttpResponse(200, [], json_encode([
                        'country' => [
                            'alpha2' => self::BIN_MAP[$matches[0]]
                        ]
                    ]));
                }

                throw new ClientException(
                    'Not found',
                    new HttpRequest('GET', $url),
                    new HttpResponse(404)
                );
            });

        return new BinListNetProvider($httpClient);
    }

    protected function getExchangeProvider(): ExchangeRatesApiProvider
    {
        $exchangeRates = json_encode([
            'rates' => [
                'USD' => 1.1773,
                'GBP' => 0.91213,
                'JPY' => 125.05
            ]
        ]);

        $httpClient = $this->createMock(HttpClient::class);
        $httpClient->method('request')
            ->willReturn(new HttpResponse(200, [], $exchangeRates));

        return new ExchangeRatesApiProvider($httpClient);
    }
}
