<?php

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Response as HttpResponse;
use Matasar\CommissionCalculator\ExchangeRatesProviders\ExchangeRatesApiProvider;
use PHPUnit\Framework\TestCase;

final class ExchangeRatesApiProviderTest extends TestCase
{
    private const USD_RATE = 1.1773;

    private $provider;

    public function testGetExchangeRate()
    {
        $exchangeRate = $this->provider->getExchangeRate('USD');

        $this->assertEquals( self::USD_RATE, $exchangeRate->getRate());
    }

    public function testWrongCurrencyProvided()
    {
        $this->expectException('Matasar\CommissionCalculator\Exceptions\ProviderException');

        $this->provider->getExchangeRate('ABC');
    }

    public function testBadResponse()
    {
        $fakeHttpClient = $this->createMock(HttpClient::class);
        $fakeHttpClient->method('request')
            ->willReturn(new HttpResponse());

        $provider = new ExchangeRatesApiProvider($fakeHttpClient);

        $this->expectException('Matasar\CommissionCalculator\Exceptions\ProviderException');

        $provider->getExchangeRate('USD');
    }

    protected function setUp(): void
    {
        $response = new HttpResponse(200, [], json_encode([
            'rates' => [
                'USD' => self::USD_RATE
            ]
        ]));

        $httpClient = $this->createMock(HttpClient::class);
        $httpClient->method('request')
            ->willReturn($response);

        $this->provider = new ExchangeRatesApiProvider($httpClient);
    }
}
