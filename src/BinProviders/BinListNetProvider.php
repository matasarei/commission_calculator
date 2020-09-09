<?php

namespace Matasar\CommissionCalculator\BinProviders;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Matasar\CommissionCalculator\Interfaces\BinProviderInterface;
use Psr\Http\Message\ResponseInterface;

class BinListNetProvider implements BinProviderInterface
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param int $bin
     *
     * @return string Country ISO / alpha2 code
     *
     * @throws GuzzleException
     * @throws \UnexpectedValueException
     */
    public function getCountryIsoByBin(int $bin): string
    {
        $response = $this->httpRequest($bin);

        $data = json_decode($response->getBody()->getContents(), true);
        $country = $data['country']['alpha2'] ?? null;

        if (null === $country) {
            throw new \UnexpectedValueException('Bad response, fail to get country iso.');
        }

        return $country;
    }

    /**
     * @param string $request
     *
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    protected function httpRequest(string $request): ResponseInterface
    {
        return $this->httpClient->request('GET', 'https://lookup.binlist.net/' . $request);
    }
}
