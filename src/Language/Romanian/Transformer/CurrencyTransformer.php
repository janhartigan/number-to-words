<?php

namespace Kwn\NumberToWords\Language\Romanian\Transformer;

use Kwn\NumberToWords\Language\Romanian\Dictionary\Currency;
use Kwn\NumberToWords\Language\Romanian\Dictionary\Language;
use Kwn\NumberToWords\Model\Amount;
use Kwn\NumberToWords\Model\Number;
use Kwn\NumberToWords\Transformer\CurrencyTransformer as CurrencyTransformerInterface;

class CurrencyTransformer implements CurrencyTransformerInterface
{
    /**
     * @var NumberTransformer
     */
    private $transformer;

    /**
     * @param NumberTransformer $transformer
     */
    public function __construct(NumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Converts a currency value to its word representation
     * (with monetary units) in Romanian
     *
     * @param string $int_curr         An international currency symbol
     *                                  as defined by the ISO 4217 standard (three characters)
     * @param integer $decimal          A money total amount without fraction part (e.g. amount of dollars)
     * @param integer $fraction         Fractional part of the money amount (e.g. amount of cents)
     *                                  Optional. Defaults to false.
     * @param integer $convert_fraction Convert fraction to words (left as numeric if set to false).
     *                                  Optional. Defaults to true.
     *
     * @return string  The corresponding word representation for the currency
     *
     * @access public
     * @author Bogdan Stăncescu <bogdan@moongate.ro>
     */
    private function toCurrencyWords($int_curr, $decimal, $fraction = false, $convert_fraction = true)
    {
        $int_curr = strtoupper($int_curr);
        if (!isset(Currency::getCurrencyNames()[$int_curr])) {
            $int_curr = 'EUR';
        }

        $curr_nouns = Currency::getCurrencyNames()[$int_curr];
        $ret        = $this->transformer->toWords(new Number($decimal), $curr_nouns[0]);

        if ($fraction !== false) {
            $ret .= ' ' . Language::WORD_AND;
            if ($convert_fraction) {
                $ret .= ' ' . $this->toWords(
                        new Amount(new Number($fraction), new \Kwn\NumberToWords\Model\Currency($int_curr)),
                        $curr_nouns[1]
                    );
            } else {
                $ret .= $fraction . ' ';
                $plural_rule = $this->_get_plural_rule($fraction);
                $this->_get_noun_declension_for_number($plural_rule, $curr_nouns[1]);
            }
        }

        return trim($ret);
    }

    /**
     * @param Amount $amount
     *
     * @return string
     */
    public function toWords(Amount $amount)
    {
        $decimalPoint = '.';

        $amountValue = round($amount->getNumber()->getValue(), 2);

        if (strpos($amountValue, $decimalPoint) === false) {
            return trim($this->toCurrencyWords($amount->getCurrency()->getIdentifier(), $amountValue));
        }

        $currency = explode($decimalPoint, $amountValue, 2);

        $len = strlen($currency[1]);

        if ($len === 1) {
            $currency[1] .= '0';
        }

        return $this->toCurrencyWords($amount->getCurrency()->getIdentifier(), $currency[0], $currency[1]);
    }
}
