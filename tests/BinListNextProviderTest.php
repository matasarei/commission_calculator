<?php

use GuzzleHttp\Client as HttpClient;
use Matasar\CommissionCalculator\BinProviders\BinListNetProvider;
use PHPUnit\Framework\TestCase;

final class BinListNextProviderTest extends TestCase
{
    private $provider;

    public function testGetCountryByBin()
    {
        $country = $this->provider->getCountryIsoByBin(45717360);

        $this->assertEquals('DK', $country);
    }

    public function testBinNotFound()
    {
        $this->expectException('GuzzleHttp\Exception\ClientException');

        $this->provider->getCountryIsoByBin(0);
    }

    protected function setUp(): void
    {
        $this->provider = new BinListNetProvider(new HttpClient());
    }
}
