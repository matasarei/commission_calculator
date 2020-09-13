<?php

namespace Matasar\CommissionCalculator\ExchangeRatesProviders;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use Matasar\CommissionCalculator\Entities\CurrencyExchangeRate;
use Matasar\CommissionCalculator\Exceptions\ProviderException;
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
     * @return CurrencyExchangeRate
     *
     * @throws ProviderException
     */
    public function getExchangeRate($currencyIso): CurrencyExchangeRate
    {
        $this->loadExchangeRates();

        if (isset($this->exchangeData[$currencyIso])) {
            return new CurrencyExchangeRate($currencyIso, $this->exchangeData[$currencyIso]);
        }

        throw new ProviderException(
            sprintf('Wrong currency code provided, fail to get exchange rate for "%s"', $currencyIso)
        );
    }

    /**
     * @throws ProviderException
     */
    protected function loadExchangeRates()
    {
        if (null !== $this->exchangeData) {
            return;
        }

        try {
            $response = $this->httpClient->request('GET', 'https://api.exchangeratesapi.io/latest');
        } catch (BadResponseException $ex) {
            throw new ProviderException($ex->getMessage(), 0, $ex);
        }

        $data = json_decode($response->getBody()->getContents(), true);
        $this->exchangeData = $data['rates'] ?? null;

        if (!is_array($this->exchangeData)) {
            throw new ProviderException('Unexpected response, fail to load exchange rates.');
        }
    }
}
