<?php

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Response as HttpResponse;
use Matasar\CommissionCalculator\BinProviders\BinListNetProvider;
use PHPUnit\Framework\TestCase;

final class BinListNextProviderTest extends TestCase
{
    public function testGetCountryByBin()
    {
        $response = new HttpResponse(200, [], json_encode([
            'country' => [
                'alpha2' => 'DK'
            ]
        ]));

        $httpClient = $this->getHttpClientMock($response);

        $provider = new BinListNetProvider($httpClient);
        $data = $provider->getBinData(45717360);

        $this->assertEquals('DK', $data->getCountryCode());
    }

    public function testBadResponse()
    {
        $provider = new BinListNetProvider($this->getHttpClientMock(new HttpResponse(400)));

        $this->expectException('Matasar\CommissionCalculator\Exceptions\ProviderException');

        $provider->getBinData(0);
    }

    /**
     * @param HttpResponse $response
     *
     * @return HttpClient|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getHttpClientMock(HttpResponse $response)
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient->method('request')
            ->willReturn($response);

        return $httpClient;
    }
}
