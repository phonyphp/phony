<?php

namespace Phony\Fake\Standard;

use Phony\Fake\Fake;

/**
 * Class Boolean.
 *
 *
 * @property-read bool boolean
 */
class Boolean extends Fake
{
    protected array $methodsAsAttributes = [
        'boolean' => [50.0],
    ];

    /**
     * Returns a random boolean value whether true or false.
     *
     * @param  float  $truePercentage  Probability of receiving true value
     *
     * @return bool
     */
    public function boolean(float $truePercentage = 50.0): bool
    {
        if ($this->phony->number->integerBetween(1, 100) <= $truePercentage) {
            return true;
        }

        return false;
    }
}
