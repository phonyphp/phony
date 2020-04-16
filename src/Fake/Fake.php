<?php

namespace Phony\Fake;

use Phony\Phony;
use RuntimeException;

/**
 * Class Fake.
 */
class Fake
{
    protected Phony $phony;
    protected array $attributes;
    protected array $functionAttributes;
    protected array $functions;
    protected array $functionAliases;

    /**
     * Fake constructor.
     *
     * @param  \Phony\Phony  $phony
     */
    public function __construct(Phony $phony)
    {
        $this->phony = $phony;
    }

    // region Magic Setup

    /**
     * Get attributes by magic.
     *
     * @param $attribute
     *
     * @return mixed
     * @throws \Exception
     */
    public function __get($attribute)
    {
        if (isset($this->attributes[$attribute])) {
            $value = $this->fetch($this->attributes[$attribute][0]);

            if (isset($this->attributes[$attribute][1])) {
                $functions = explode('|', $this->attributes[$attribute][1]);

                foreach ($functions as $fn) {
                    $value = $this->$fn($value);
                }
            }

            return $value;
        }

        if (isset($this->functionAliases[$attribute])) {
            return $this->{$this->functionAliases[$attribute]}();
        }

        throw new RuntimeException("The {$attribute} attribute is not defined!");
    }

    /**
     * Don't allow setting magic attributes.
     *
     * @param $attribute
     * @param $value
     */
    public function __set($attribute, $value)
    {
        throw new RuntimeException("Setting {$attribute} attribute is not allowed!");
    }

    /**
     * Check if a magic attribute exists.
     *
     * @param $attribute
     *
     * @return bool
     */
    public function __isset($attribute)
    {
        if (isset($this->attributes[$attribute])) {
            return true;
        }

        return false;
    }

    /**
     * Get function by magic.
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (isset($this->functionAttributes[$name])) {
            return $this->{$this->functionAttributes[$name][0]}(...$this->functionAttributes[$name][1]);
        }

        throw new RuntimeException("The {$name} function is not defined!");
    }

    // endregion

    // region Fetching

    /**
     * Fetches a value.
     *
     * @param  string       $key
     *
     * @param  string|null  $path
     *
     * @return string
     */
    protected function fetch(string $key, string $path = null): string
    {
        $template = $this->phony->loader->get($key, $path, $this->phony->defaultLocale);

        // Pick one if it's an array
        if (is_array($template)) {
            $template = $template[array_rand($template, 1)];
        }

        // Check if it's an actual fake data or a template
        if (strpos($template, '🙃') === false) {
            // It's a fake data, so return it immediately.
            return $template;
        }

        // Extract placeholders in the template
        $placeholders = [];
        preg_match_all('/🙃(.*?)🙃/u', $template, $placeholders);

        // Fetch placeholder values recursively
        $values = [];
        foreach ($placeholders[1] as $index => $placeholder) {
            $values[$placeholders[0][$index]] = $this->fetch($placeholder);
        }

        return strtr($template, $values);
    }

    /**
     * Fetches multiple values given number of times.
     *
     * @param  int     $times
     * @param  bool    $asString
     * @param  string  $glue
     * @param          $callback
     *
     * @return array|string
     */
    protected function fetchMany(int $times, bool $asString, string $glue, $callback)
    {
        $values = [];

        foreach (range(1, $times) as $i) {
            $values[] = $callback();
        }

        return $asString
            ? implode($glue, $values)
            : $values;
    }

    // endregion

    // region Random Numbers

    /**
     * Returns a random number between 0 and 9.
     *
     * @return int
     *
     * @throws \Exception
     */
    protected function randomDigit(): int
    {
        return random_int(0, 9);
    }

    /**
     * Returns a random number between 1 and 9.
     *
     * @return int
     *
     * @throws \Exception
     */
    protected function randomDigitNotNull(): int
    {
        return random_int(1, 9);
    }

    /**
     * Returns a random hex letter between 0 and f.
     *
     * @return string
     * @throws \Exception
     */
    protected function randomHexLetter(): string
    {
        return dechex(random_int(0, 15));
    }

    /**
     * Returns a random integer with 0 to $nbDigits digits.
     *
     * @param  int  $nbDigits
     *
     * @return int
     * @throws \Exception
     */
    protected function randomNumber(int $nbDigits = null): int
    {
        if ($nbDigits === null) {
            $nbDigits = $this->randomDigitNotNull();
        }

        $max = (10 ** $nbDigits) - 1;

        return random_int(0, $max);
    }

    /**
     * @param  int  $min
     * @param  int  $max
     *
     * @return int
     * @throws \Exception
     */
    protected function numberBetween(int $min, int $max): int
    {
        return $min < $max
            ? random_int($min, $max)
            : random_int($max, $min);
    }

    /**
     * @param  int|null  $max
     * @param  int|null  $min
     * @param  int|null  $nbMaxDecimals
     *
     * @return float
     * @throws \Exception
     */
    public function randomFloat(int $max = null, int $min = 0, int $nbMaxDecimals = null): float
    {
        if ($nbMaxDecimals === null) {
            $nbMaxDecimals = $this->randomDigit();
        }

        if ($max === null) {
            $max = $this->randomNumber();
        }

        if ($min > $max) {
            $tmp = $min;
            $min = $max;
            $max = $tmp;
        }

        return round(random_int($min, $max) + (random_int(0, PHP_INT_MAX) / PHP_INT_MAX), $nbMaxDecimals);
    }

    // endregion

    // region Numerifications

    /**
     * Replaces all hash sign ('#') occurrences with a random number and
     * all percentage sign ('%') occurrences with a not null number.
     *
     * @param  array|string  $numberString
     *
     * @return array|string
     * @throws \Exception
     */
    protected function numerify($numberString = '%%%###')
    {
        if (is_array($numberString)) {
            foreach ($numberString as $index => $item) {
                $numberString[$index] = $this->numerify($item);
            }
        }

        $numberString = preg_replace_callback('/%/', fn () => $this->randomDigitNotNull(), $numberString);
        $numberString = preg_replace_callback('/#/', fn () => $this->randomDigit(), $numberString);

        return $numberString;
    }

    /**
     * Replaces all question mark ('?') occurrences with a random hex character.
     *
     * @param  array|string  $letterString
     *
     * @return array|string
     * @throws \Exception
     */
    protected function hexify($letterString = '####')
    {
        if (is_array($letterString)) {
            foreach ($letterString as $index => $item) {
                $letterString[$index] = $this->hexify($item);
            }
        }

        return preg_replace_callback(
            '/#/',
            fn () => $this->randomHexLetter(),
            $letterString
        );
    }

    /**
     * Replaces all question mark ('?') occurrences with a random letter.
     *
     * @param  array|string  $letterString
     *
     * @return array|string
     * @throws \Exception
     */
    protected function letterify($letterString = '????')
    {
        if (is_array($letterString)) {
            foreach ($letterString as $index => $item) {
                $letterString[$index] = $this->letterify($item);
            }
        }

        return preg_replace_callback(
            '/\?/',
            fn () => $this->fetch('alphabet.letter'),
            $letterString
        );
    }

    /**
     * Replaces hash signs ('#') and question marks ('?') with random numbers and letters.
     * An asterisk ('*') is replaced with either a random number or a random letter.
     *
     * @param  array|string  $string
     *
     * @return array|string
     * @throws \Exception
     */
    protected function bothify($string = '##??**')
    {
        if (is_array($string)) {
            foreach ($string as $index => $item) {
                $string[$index] = $this->bothify($item);
            }
        }

        $string = $this->letterify($this->numerify($string));

        $string = preg_replace_callback(
            '/\*/',
            fn () => random_int(0, 1)
                ? $this->numerify('#')
                : $this->letterify('?'),
            $string
        );

        return $string;
    }

    // endregion
}
