<?php

namespace Matasar\CommissionCalculator\ExchangeRatesProviders;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Matasar\CommissionCalculator\Interfaces\CurrencyRatesProviderInterface;

class ExchangeRatesApiProvider implements CurrencyRatesProviderInterface
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var array|null
     */
    protected $exchangeData;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param $currencyIso
     *
     * @return float|null
     *
     * @throws GuzzleException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    public function getExchangeRate($currencyIso): float
    {
        $this->loadExchangeRates();

        if (isset($this->exchangeData[$currencyIso])) {
            return (float) $this->exchangeData[$currencyIso];
        }

        throw new \InvalidArgumentException(
            sprintf('Wrong currency code provided, fail to get exchange rate for "%s"', $currencyIso)
        );
    }

    /**
     * @throws GuzzleException
     * @throws \UnexpectedValueException
     */
    protected function loadExchangeRates()
    {
        if (null !== $this->exchangeData) {
            return;
        }

        $response = $this->httpClient->request('GET', 'https://api.exchangeratesapi.io/latest');
        $data = json_decode($response->getBody()->getContents(), true);

        $this->exchangeData = $data['rates'] ?? null;

        if (!is_array($this->exchangeData)) {
            throw new \UnexpectedValueException('Unexpected response, fail to load exchange rates.');
        }
    }
}
