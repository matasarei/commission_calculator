<?php

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Response as HttpResponse;
use Matasar\CommissionCalculator\ExchangeRatesProviders\ExchangeRatesApiProvider;
use PHPUnit\Framework\TestCase;

final class ExchangeRatesApiProviderTest extends TestCase
{
    private $provider;

    public function testGetExchangeRate()
    {
        $this->assertNotNull($this->provider->getExchangeRate('USD'));
    }

    public function testWrongCurrencyProvided()
    {
        $this->expectException('InvalidArgumentException');

        $this->provider->getExchangeRate('ABC');
    }

    public function testUnexpectedServerResponse()
    {
        $fakeHttpClient = $this->createMock(HttpClient::class);
        $fakeHttpClient->method('request')
            ->willReturn(new HttpResponse());

        $provider = new ExchangeRatesApiProvider($fakeHttpClient);

        $this->expectException('UnexpectedValueException');

        $provider->getExchangeRate('USD');
    }

    protected function setUp(): void
    {
        $this->provider = new ExchangeRatesApiProvider(new HttpClient());
    }
}
