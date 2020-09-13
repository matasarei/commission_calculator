<?php

namespace Matasar\CommissionCalculator\BinProviders;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Matasar\CommissionCalculator\Entities\BinData;
use Matasar\CommissionCalculator\Exceptions\ProviderException;
use Matasar\CommissionCalculator\Interfaces\BinProviderInterface;

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
     * @return BinData
     *
     * @throws ProviderException
     */
    public function getBinData(int $bin): BinData
    {
        try {
            $response = $this->httpClient->request('GET', 'https://lookup.binlist.net/' . $bin);
        } catch (GuzzleException $ex) {
            throw new ProviderException($ex->getMessage(), 0, $ex);
        }

        $data = json_decode($response->getBody()->getContents(), true);
        $country = $data['country']['alpha2'] ?? null;

        if (null === $country) {
            throw new ProviderException('Bad response, fail to get country iso.');
        }

        return new BinData($bin, $data['country']['alpha2']);
    }
}
