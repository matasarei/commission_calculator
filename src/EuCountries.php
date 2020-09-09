<?php

namespace Matasar\CommissionCalculator;

class EuCountries
{
    private static $countryCodes = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];

    /**
     * @param string $countryIso
     *
     * @return bool
     */
    public static function isEuCountry(string $countryIso)
    {
        return in_array($countryIso, self::$countryCodes, true);
    }
}
