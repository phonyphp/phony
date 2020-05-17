<?php

namespace Phony\Test\Group\Standard;

use Error;
use Phony\Test\BaseTest;
use RangeException;

class NumberTest extends BaseTest
{
    // region integerBetween()

    /** @test */
    public function integerBetween_method_returns_an_integer(): void
    {
        $value = $this->🙃->number->integerBetween();

        $this->assertIsInt($value);
    }

    /** @test */
    public function integerBetween_method_returns_an_integer_between_min_and_max(): void
    {
        $value = $this->🙃->number->integerBetween(1, 100);

        $this->assertGreaterThanOrEqual(1, $value);
        $this->assertLessThanOrEqual(100, $value);
    }

    /** @test */
    public function integerBetween_method_returns_error_if_min_greater_than_max(): void
    {
        $this->expectException(Error::class);

        $this->🙃->number->integerBetween(2, 1);
    }

    // endregion

    // region integerWithin()

    /** @test */
    public function integerWithin_method_returns_an_integer(): void
    {
        $value = $this->🙃->number->integerWithin();

        $this->assertIsInt($value);
    }

    /** @test */
    public function integerWithin_method_returns_an_integer_that_the_boundaries_not_included(): void
    {
        $value = $this->🙃->number->integerWithin(1, 100);

        $this->assertGreaterThanOrEqual(2, $value);
        $this->assertLessThanOrEqual(99, $value);
    }

    /** @test */
    public function integerWithin_method_returns_error_if_min_greater_than_max(): void
    {
        $this->expectException(Error::class);

        $this->🙃->number->integerWithin(2, 1);
    }

    /** @test */
    public function integerWithin_method_returns_error_if_min_and_max_are_same(): void
    {
        $this->expectException(Error::class);

        $this->🙃->number->integerWithin(1, 1);
    }

    // endregion

    // region integerPositive()

    /** @test */
    public function integerPositive_method_returns_an_integer(): void
    {
        $value = $this->🙃->number->integerPositive();

        $this->assertIsInt($value);
    }

    /** @test */
    public function integerPositive_method_returns_a_positive_integer(): void
    {
        $value = $this->🙃->number->integerPositive();

        $this->assertGreaterThanOrEqual(1, $value);
    }

    /** @test */
    public function integerPositive_method_returns_error_if_min_is_not_positive(): void
    {
        $this->expectException(Error::class);

        $this->🙃->number->integerPositive(-1);
    }

    /** @test */
    public function integerPositive_method_returns_error_if_min_is_zero(): void
    {
        $this->expectException(Error::class);

        $this->🙃->number->integerPositive(0);
    }

    // endregion

    // region integerNegative()

    /** @test */
    public function integerNegative_method_returns_an_integer(): void
    {
        $value = $this->🙃->number->integerNegative();

        $this->assertIsInt($value);
    }

    /** @test */
    public function integerNegative_method_returns_a_negative_integer(): void
    {
        $value = $this->🙃->number->integerNegative();

        $this->assertLessThanOrEqual(-1, $value);
    }

    /** @test */
    public function integerNegative_method_returns_error_if_max_is_not_negative(): void
    {
        $this->expectException(Error::class);

        $this->🙃->number->integerNegative(1);
    }

    /** @test */
    public function integerNegative_method_returns_error_if_max_is_zero(): void
    {
        $this->expectException(Error::class);

        $this->🙃->number->integerNegative(0);
    }

    // endregion

    // region integer()

    /** @test */
    public function integer_method_returns_an_integer(): void
    {
        $value = $this->🙃->number->integer();

        $this->assertIsInt($value);
    }

    /** @test */
    public function integer_method_returns_an_integer_with_the_given_number_of_digits(): void
    {
        $digits = random_int(1, 15);
        $value = $this->🙃->number->integer($digits);

        $this->assertLessThanOrEqual($digits, strlen((string) abs($value)));
    }

    /** @test */
    public function integer_method_returns_an_integer_with_exactly_the_given_number_of_digits(): void
    {
        $digits = random_int(1, 15);
        $value = $this->🙃->number->integer($digits, true);

        $this->assertEquals($digits, strlen((string) abs($value)));
    }

    /** @test */
    public function integer_method_returns_an_positive_or_negative_integers(): void
    {
        $value = $this->🙃->number->integer(1, true, true);
        $this->assertGreaterThan(0, $value);

        $value = $this->🙃->number->integer(1, true, false);
        $this->assertLessThan(0, $value);
    }

    // endregion

    // region integerLeadingZero()

    /** @test */
    public function integerLeadingZero_method_returns_a_string(): void
    {
        $value = $this->🙃->number->integerLeadingZero();

        $this->assertIsString($value);
    }

    /** @test */
    public function integerLeadingZero_method_returns_a_string_leading_with_zeros(): void
    {
        $value = $this->🙃->number->integerLeadingZero(10);

        $this->assertMatchesRegularExpression('/^^(0{0,10}[0-9]{0,10}){1}$/', $value);
    }

    // endregion

    // region integerNormal()

    /** @test */
    public function integerNormal_method_returns_an_integer(): void
    {
        $value = $this->🙃->number->integerNormal();

        $this->assertIsInt($value);
    }

    /** @test */
    public function integerNormal_method_calculates_integers_with_standard_deviation(): void
    {
        $n = 10000;

        $values = [];
        foreach (range(1, 10000) as $k => $i) {
            $values[] = $this->🙃->number->integerNormal(150, 100);
        }

        $mean = array_sum($values) / (float) $n;

        $variance = array_reduce($values, function ($variance, $item) use ($mean) {
            return $variance += ($item - $mean) ** 2;
        }, 0) / (float) ($n - 1);

        $std_dev = sqrt($variance);

        $this->assertEqualsWithDelta(150, $mean, 5);
        $this->assertEqualsWithDelta(100, $std_dev, 3);
    }

    // endregion

    // region integerExcept()

    /** @test */
    public function integerExcept_method_returns_an_integer(): void
    {
        $value = $this->🙃->number->integerExcept();

        $this->assertIsInt($value);
    }

    /** @test */
    public function integerExcept_method_returns_an_integer_except_the_given_integer(): void
    {
        $value = $this->🙃->number->integerExcept(2, 1, 2);

        $this->assertEquals(1, $value);
    }

    /** @test */
    public function integerExcept_method_returns_an_integer_except_the_given_array_of_integers(): void
    {
        $value = $this->🙃->number->integerExcept([1, 2, 3, 4], 1, 5);

        $this->assertEquals(5, $value);
    }

    /** @test */
    public function integerExcept_method_throws_RangeException_if_there_are_not_enough_integers(): void
    {
        $this->expectException(RangeException::class);

        $this->🙃->number->integerExcept([1, 2, 3, 4, 5], 1, 5);
    }

    // endregion

    // region digit()

    /** @test */
    public function digit_method_returns_an_integer(): void
    {
        $value = $this->🙃->number->digit();

        $this->assertIsInt($value);
    }

    /** @test */
    public function digit_method_returns_a_digit(): void
    {
        $value = $this->🙃->number->digit();

        $this->assertGreaterThanOrEqual(0, $value);
        $this->assertLessThanOrEqual(9, $value);
    }

    /** @test */
    public function digit_method_returns_a_digit_for_the_given_base(): void
    {
        $valueBase2 = $this->🙃->number->digit(2);

        $this->assertGreaterThanOrEqual(0, $valueBase2);
        $this->assertLessThan(2, $valueBase2);

        $base = random_int(2, 99);
        $value = $this->🙃->number->digit($base);

        $this->assertGreaterThanOrEqual(0, $value);
        $this->assertLessThan($base, $value);
    }

    // endregion

    // region digitExcept()

    /** @test */
    public function digitExcept_method_returns_an_integer(): void
    {
        $value = $this->🙃->number->digitExcept();

        $this->assertIsInt($value);
    }

    /** @test */
    public function digitExcept_method_returns_a_digit_except_given_digit(): void
    {
        $value = $this->🙃->number->digitExcept(1, 2);
        $this->assertNotEquals(1, $value);

        $value = $this->🙃->number->digitExcept(0, 2);
        $this->assertNotEquals(0, $value);

        $value = $this->🙃->number->digitExcept(1, 2);
        $this->assertNotEquals(1, $value);
    }

    // endregion

    // region digitNonZero()

    /** @test */
    public function digitNonZero_method_returns_an_integer(): void
    {
        $value = $this->🙃->number->digitNonZero();

        $this->assertIsInt($value);
    }

    /** @test */
    public function digitNonZero_method_returns_a_digit_that_is_not_zero(): void
    {
        $value = $this->🙃->number->digitNonZero(2);
        $this->assertEquals(1, $value);

        $value = $this->🙃->number->digitNonZero();
        $this->assertNotEquals(0, $value);
    }

    // endregion

    // region floatBetween()

    /** @test */
    public function floatBetween_method_returns_a_float(): void
    {
        $value = $this->🙃->number->floatBetween();

        $this->assertIsFloat($value);
    }

    /** @test */
    public function floatBetween_method_returns_a_float_between_min_and_max(): void
    {
        $value = $this->🙃->number->floatBetween(0.0, 1.0);

        $this->assertGreaterThanOrEqual(0, $value);
        $this->assertLessThanOrEqual(1, $value);
    }

    /** @test */
    public function floatBetween_method_returns_a_float_with_given_precision(): void
    {
        $precision = random_int(0, 14);
        $value = $this->🙃->number->floatBetween(0.0, 1.0, $precision);

        $this->assertLessThanOrEqual($precision + 2, strlen($value));
    }

    // endregion

    // region floatPositive()

    /** @test */
    public function floatPositive_method_returns_a_float(): void
    {
        $value = $this->🙃->number->floatPositive();

        $this->assertIsFloat($value);
    }

    /** @test */
    public function floatPositive_method_returns_a_positive_float(): void
    {
        $value = $this->🙃->number->floatPositive();

        $this->assertGreaterThanOrEqual(0, $value);
    }

    /** @test */
    public function floatPositive_method_returns_zero_if_max_is_zero(): void
    {
        $value = $this->🙃->number->floatPositive(0);

        $this->assertEquals(0, $value);
    }

    /** @test */
    public function floatPositive_method_returns_a_float_with_given_precision(): void
    {
        $precision = random_int(0, 14);
        $value = $this->🙃->number->floatPositive(1, $precision);

        $this->assertLessThanOrEqual($precision + 2, strlen($value));
    }

    // endregion

    // region floatNegative()

    /** @test */
    public function floatNegative_method_returns_a_float(): void
    {
        $value = $this->🙃->number->floatNegative();

        $this->assertIsFloat($value);
    }

    /** @test */
    public function floatNegative_method_returns_a_negative_float(): void
    {
        $value = $this->🙃->number->floatNegative();

        $this->assertLessThan(0, $value);
    }

    /** @test */
    public function floatNegative_method_returns_a_float_with_given_precision(): void
    {
        $precision = random_int(0, 14);
        $value = $this->🙃->number->floatNegative(-1, $precision);

        $this->assertLessThanOrEqual($precision + 3, strlen($value));
    }

    // endregion

    // region float()

    /** @test */
    public function float_method_returns_a_float(): void
    {
        $value = $this->🙃->number->float();

        $this->assertIsFloat($value);
    }

    // endregion

    // region floatNormal()

    /** @test */
    public function floatNormal_method_returns_a_float(): void
    {
        $value = $this->🙃->number->floatNormal();

        $this->assertIsFloat($value);
    }

    /** @test */
    public function floatNormal_method_calculates_floats_with_standard_deviation(): void
    {
        $n = 10000;

        $values = [];
        foreach (range(1, 10000) as $k => $i) {
            $values[] = $this->🙃->number->floatNormal(150.0, 100.0);
        }

        $mean = array_sum($values) / (float) $n;

        $variance = array_reduce($values, function ($variance, $item) use ($mean) {
            return $variance += ($item - $mean) ** 2;
        }, 0) / (float) ($n - 1);

        $std_dev = sqrt($variance);

        $this->assertEqualsWithDelta(150, $mean, 5);
        $this->assertEqualsWithDelta(100, $std_dev, 3);
    }

    // endregion

    // region possibleIntegersCount()

    /**
     * @test
     *
     * @dataProvider possibleIntegersCountProvider
     *
     * @param  int  $min
     * @param  int  $max
     * @param  int  $expected
     */
    public function possibleIntegersCount_method(int $min, int $max, int $expected): void
    {
        $possibilities = $this->callPrivateMethod($this->🙃->number, 'possibleIntegersCount', $min, $max);

        $this->assertEquals($expected, $possibilities);
    }

    public function possibleIntegersCountProvider(): array
    {
        return [
            [1, 5, 5],
            [0, 5, 6],
            [-5, 5, 11],
            [-5, 0, 6],
            [-10, -5, 6],
            [0, 0, 1],
            [1, 1, 1],
        ];
    }

    // endregion
}
