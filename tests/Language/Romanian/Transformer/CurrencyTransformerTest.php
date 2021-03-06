<?php

namespace Kwn\NumberToWords\Language\Romanian\Transformer;

use Kwn\NumberToWords\Model\Amount;
use Kwn\NumberToWords\Model\Currency;
use Kwn\NumberToWords\Model\Number;
use Kwn\NumberToWords\Model\SubunitFormat;

class CurrencyTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerToWords
     */
    public function testToWords($expectedValue, Amount $amount)
    {
        $transformer = new CurrencyTransformer(new NumberTransformer());

        $this->assertEquals($expectedValue, $transformer->toWords($amount));
    }

    public function providerToWords()
    {
        return [
            [
                'un leu',
                new Amount(new Number(1), new Currency('ROL'), new SubunitFormat(SubunitFormat::WORDS))
            ],
            [
                'un leu',
                new Amount(new Number(1.00), new Currency('ROL'), new SubunitFormat(SubunitFormat::WORDS))
            ],
            [
                'doi lei',
                new Amount(new Number(2), new Currency('ROL'), new SubunitFormat(SubunitFormat::WORDS))
            ],
            [
                'două mii de lei',
                new Amount(new Number(2000), new Currency('ROL'), new SubunitFormat(SubunitFormat::WORDS))
            ],
            [
                'un leu și patruzeci și cinci de lei',
                new Amount(new Number(1.45), new Currency('ROL'), new SubunitFormat(SubunitFormat::WORDS))
            ],
            [
                'un leu și patruzeci de lei',
                new Amount(new Number(1.40), new Currency('ROL'), new SubunitFormat(SubunitFormat::WORDS))
            ],
            [
                'un leu și patruzeci de lei',
                new Amount(new Number(1.4), new Currency('ROL'), new SubunitFormat(SubunitFormat::WORDS))
            ],
            [
                'un leu și patruzeci de lei',
                new Amount(new Number(1.4000), new Currency('ROL'), new SubunitFormat(SubunitFormat::WORDS))
            ]
        ];
    }
}
