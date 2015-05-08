<?php

namespace Kwn\NumberToWords\Language\Polish\Transformer;

use Kwn\NumberToWords\Model\Number;
use Kwn\NumberToWords\Language\Polish\Dictionary\Number as NumberDictionary;

class NumberTransformer extends AbstractTransformer
{
    /**
     * Return number converted to words
     *
     * @param Number $number
     *
     * @return string
     */
    public function toWords(Number $number)
    {
        $value = $number->getValue();

        if ($value === 0.0) {
            return NumberDictionary::$zero;
        }

        $triplets = [];

        while ($value > 0) {
            $triplets[] = $value % 1000;
            $value      = (int) ($value / 1000);
        }

        $words = [];

        foreach ($triplets as $i => $triplet) {
            if ($triplet > 0) {
                $threeDigitsWords = $this->threeDigitsToWords($triplet);

                if ($i > 0) {
                    $case = $this->grammarCase($triplet);
                    $mega = NumberDictionary::$mega[$i][$case];

                    $threeDigitsWords = $threeDigitsWords . ' ' . $mega;
                }

                $words[] = $threeDigitsWords;
            }
        }

        return join(' ', array_reverse($words));
    }

    /**
     * Return triplets in words
     *
     * @param $value
     *
     * @return string
     */
    private function threeDigitsToWords($value)
    {
        $units    = $value % 10;
        $tens     = (int) ($value / 10) % 10;
        $hundreds = (int) ($value / 100) % 10;

        $words = [];

        if ($hundreds > 0) {
            $words[] = NumberDictionary::$hundreds[$hundreds];
        }

        if ($tens === 1) {
            $words[] = NumberDictionary::$teens[$units];
        } else {
            if ($tens > 0) {
                $words[] = NumberDictionary::$tens[$tens];
            }
            if ($units > 0) {
                $words[] = NumberDictionary::$units[$units];
            }
        }

        return join(' ', $words);
    }
}