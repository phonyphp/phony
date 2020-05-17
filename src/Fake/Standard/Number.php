<?php

namespace Phony\Fake\Standard;

use Exception;
use Phony\Fake\Fake;
use RangeException;

/**
 * Class Number.
 */
class Number extends Fake
{
    /**
     * Fakes a random integer between $min and $max.
     *
     * @param  int  $min
     * @param  int  $max
     *
     * @return int
     */
    public function integerBetween(int $min = -10_000, int $max = +10_000): int
    {
        try {
            return random_int($min, $max);
        } catch (Exception $e) {
            return mt_rand($min, $max);
        }
    }

    /**
     * Fakes a random integer between $min and $max without boundaries.
     *
     * @param  int  $min
     * @param  int  $max
     *
     * @return int
     */
    public function integerWithin(int $min = -10_000, int $max = +10_000): int
    {
        return $this->integerBetween($min + 1, $max - 1);
    }

    /**
     * Fakes a random positive integer between 1 and $max.
     *
     * @param  int  $max
     *
     * @return int
     */
    public function integerPositive(int $max = 10_000): int
    {
        return $this->integerBetween(1, $max);
    }

    /**
     * Fakes a random negative integer between $min and -1.
     *
     * @param  int  $min
     *
     * @return int
     */
    public function integerNegative(int $min = -10_000): int
    {
        return $this->integerBetween($min, -1);
    }

    /**
     * Fakes a random integer with number of $digits.
     *
     * @param  int|null  $digits  Defaults to a random digit not null
     * @param  bool      $strict  Whether the returned number should have exactly $nbDigits
     * @param  bool      $isPositive
     *
     * @return int
     */
    public function integer(int $digits = null, bool $strict = false, bool $isPositive = null): int
    {
        if ($digits === null) {
            $digits = $this->digitNonZero();
        }

        $max = (10 ** $digits) - 1;

        $min = $strict
            ? 10 ** ($digits - 1)
            : 0;

        if (! isset($isPositive)) {
            $isPositive = $this->phony->boolean->boolean;
        }

        $isPositive = $isPositive ? 1 : -1;

        return $isPositive * $this->integerBetween($min, $max);
    }

    /**
     * Fakes a random integer with leading zeros.
     *
     * @param  int  $digits
     *
     * @return string
     */
    public function integerLeadingZero(int $digits = 10): string
    {
        $value = (string) $this->integer(null, false, true);

        return str_pad($value, $digits, '0', STR_PAD_LEFT);
    }

    /**
     * Fakes a random integer in a standard deviation.
     *
     * @param  int  $mean
     * @param  int  $standardDeviation
     *
     * @return int
     */
    public function integerNormal(int $mean = 10_000, int $standardDeviation = 1_000): int
    {
        return (int) $this->floatNormal($mean, $standardDeviation);
    }

    /**
     * Fakes a random integer except the given integer or array.
     *
     * @param  int|array  $except
     * @param  int        $min
     * @param  int        $max
     *
     * @return int
     */
    public function integerExcept($except = 666, int $min = -10_000, int $max = +10_000): int
    {
        if (is_int($except)) {
            $except = [$except];
        }

        if (count($except) >= $this->possibleIntegersCount($min, $max)) {
            throw new RangeException(sprintf(
                'There are not enough integers for this range. Between %s to %s, except %s',
                $min, $max, implode(', ', $except)));
        }

        do {
            $value = $this->integerBetween($min, $max);
        } while (in_array($value, $except, true));

        return $value;
    }

    /**
     * Fakes a random digit for the given base.
     *
     * @param  int  $base
     *
     * @return int
     */
    public function digit(int $base = 10): int
    {
        return $this->integerBetween(0, $base - 1);
    }

    /**
     * Fakes a random digit for the given base except the given digit.
     *
     * @param  int  $except
     * @param  int  $base
     *
     * @return int
     */
    public function digitExcept(int $except = 0, int $base = 10): int
    {
        return $this->integerExcept($except, 0, $base - 1);
    }

    /**
     * Fakes a random digit for the given base but not 0.
     *
     * @param  int  $base
     *
     * @return int
     */
    public function digitNonZero(int $base = 10): int
    {
        return $this->digitExcept(0, $base);
    }

    /**
     * Fakes a random float between $min and $max.
     *
     * @param  float  $min
     * @param  float  $max
     * @param  int    $precision
     *
     * @return float
     */
    public function floatBetween(float $min = 0, float $max = 1, int $precision = 14): float
    {
        return (float) round(lcg_value() * ($max - $min) + $min, $precision);
    }

    /**
     * Fakes a random positive float between 1 and $max.
     *
     * @param  int  $max
     * @param  int  $precision
     *
     * @return float
     */
    public function floatPositive(int $max = 10_000, int $precision = 14): float
    {
        return $this->floatBetween(0, $max, $precision);
    }

    /**
     * Fakes a random negative float between $min and -1.
     *
     * @param  int  $min
     * @param  int  $precision
     *
     * @return float
     */
    public function floatNegative(int $min = -10_000, int $precision = 14): float
    {
        return $this->floatBetween($min, 0, $precision);
    }

    /**
     * Returns a random float number between $min, $max and
     * with given number of maximum decimals.
     *
     * @param  int|null  $leftDigits   Defaults to a random digit not null
     * @param  int|null  $rightDigits  Maximum number of decimals
     * @param  bool      $strict       Whether the returned number should have exactly $nbDigits
     *
     * @return float
     */
    public function float(int $leftDigits = null, int $rightDigits = null, bool $strict = false): float
    {
        if ($leftDigits === null) {
            $leftDigits = $this->digitNonZero();
        }

        if ($rightDigits === null) {
            $rightDigits = $this->digit();
        }

        $max = (10 ** $leftDigits) - 1;

        $min = $strict
            ? 10 ** ($leftDigits - 1)
            : 0;

        return $this->floatBetween($min, $max, $rightDigits);
    }

    /**
     * Fakes a random float in a standard deviation.
     *
     * @param  float  $mean
     * @param  float  $standardDeviation
     *
     * @return float
     */
    public function floatNormal(float $mean = 50.0, float $standardDeviation = 3.0): float
    {
        $theta = 2 * M_PI * $this->floatBetween();
        $rho = sqrt(-2 * log(1 - $this->floatBetween()));
        $scale = $standardDeviation * $rho;

        return $mean + $scale * cos($theta);
    }

    /**
     * Fakes a random hex letter between 0 and f.
     *
     * @return string
     */
    public function hexadecimal(): string
    {
        return dechex($this->digit(16));
    }

    /**
     * Calculates possible integers between $min and $max.
     *
     * @param  int  $min
     * @param  int  $max
     *
     * @return int
     */
    private function possibleIntegersCount(int $min, int $max): int
    {
        if ($min > $max) {
            [$min, $max] = [$max, $min];
        }

        return $max - $min + 1;
    }
}
