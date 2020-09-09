<?php

use Matasar\CommissionCalculator\EuCountries;
use PHPUnit\Framework\TestCase;

final class EuCountriesTest extends TestCase
{
    /**
     * @dataProvider euCountriesDataProvider
     *
     * @param string $euCountryCode
     */
    public function testEuCountries(string $euCountryCode)
    {
        $this->assertTrue(EuCountries::isEuCountry($euCountryCode));
    }

    public function testNotEuCountry()
    {
        $this->assertFalse(EuCountries::isEuCountry('US'));
    }

    /**
     * @return array[]
     */
    public function euCountriesDataProvider()
    {
        return [
            ['AT'],
            ['BE'],
            ['BG'],
            ['CY'],
            ['CZ'],
            ['DE'],
            ['DK'],
            ['EE'],
            ['ES'],
            ['FI'],
            ['FR'],
            ['GR'],
            ['HR'],
            ['HU'],
            ['IE'],
            ['IT'],
            ['LT'],
            ['LU'],
            ['LV'],
            ['MT'],
            ['NL'],
            ['PO'],
            ['PT'],
            ['RO'],
            ['SE'],
            ['SI'],
            ['SK'],
        ];
    }
}
