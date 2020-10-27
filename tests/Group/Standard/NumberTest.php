<?php

// region integerBetween()

test('integerBetween() method returns an integer value', function () {
    $value = 🙃()->number->integerBetween();

    expect($value)->toBeInt();
});

test('integerBetween() method returns an integer between $min and $max', function () {
    $value = 🙃()->number->integerBetween(1, 100);

    expect($value)->toBeGreaterThanOrEqual(1);
    expect($value)->toBeLessThanOrEqual(100);
});

test('integerBetween() method returns error if $min > $max', function () {
    🙃()->number->integerBetween(2, 1);
})->throws(Error::class);

// endregion

// region integerWithin()

test('integerWithin() method returns an integer value', function () {
    $value = 🙃()->number->integerWithin();

    expect($value)->toBeInt();
});

test('integerWithin() method returns an integer that the boundaries not included', function () {
    $value = 🙃()->number->integerWithin(1, 100);

    expect($value)->toBeGreaterThanOrEqual(2);
    expect($value)->toBeLessThanOrEqual(99);
});

test('integerWithin() method returns error if $min > $max', function () {
    🙃()->number->integerWithin(2, 1);
})->throws(Error::class);

test('integerWithin() method returns error if $min === $max', function () {
    🙃()->number->integerWithin(1, 1);
})->throws(Error::class);

// endregion

// region integerPositive()

test('integerPositive() method returns an integer value', function () {
    $value = 🙃()->number->integerPositive();

    expect($value)->toBeInt();
});

test('integerPositive() method returns a positive integer', function () {
    $value = 🙃()->number->integerPositive();

    expect($value)->toBeGreaterThanOrEqual(1);
});

test('integerPositive() method returns error if $min is not positive', function () {
    🙃()->number->integerPositive(-1);
})->throws(Error::class);

test('integerPositive() method returns error if $min=0', function () {
    🙃()->number->integerPositive(0);
})->throws(Error::class);

// endregion

// region integerNegative()

test('integerNegative() method returns an integer value', function () {
    $value = 🙃()->number->integerNegative();

    expect($value)->toBeInt();
});

test('integerNegative() method returns a negative integer', function () {
    $value = 🙃()->number->integerNegative();

    expect($value)->toBeLessThanOrEqual(-1);
});

test('integerNegative() method returns error if $max is not negative', function () {
    🙃()->number->integerNegative(1);
})->throws(Error::class);

test('integerNegative() method returns error if $max=0', function () {
    🙃()->number->integerNegative(0);
})->throws(Error::class);

// endregion

// region integer()

test('integer() method returns an integer value', function () {
    $value = 🙃()->number->integer();

    expect($value)->toBeInt();
});

test('integer() method returns an integer with the given number of $digits', function () {
    $numberOfDigits = random_int(1, 15);
    $value = 🙃()->number->integer($numberOfDigits);

    expect(strlen((string) abs($value)))->toBeLessThanOrEqual($numberOfDigits);
});

test('integer() method returns an integer with exactly the given number of $digits', function () {
    $numberOfDigits = random_int(1, 15);
    $value = 🙃()->number->integer($numberOfDigits, true);

    expect(strlen((string) abs($value)))->toBeLessThanOrEqual($numberOfDigits);
});

test('integer() method returns a positive or negative integers', function () {
    $value = 🙃()->number->integer(1, true, true);
    expect($value)->toBeGreaterThan(0);

    $value = 🙃()->number->integer(1, true, false);
    expect($value)->toBeLessThan(0);
});

// endregion

// region integerLeadingZero()

test('integerLeadingZero() method returns a string value', function () {
    $value = 🙃()->number->integerLeadingZero();

    expect($value)->toBeString();
});

test('integerLeadingZero() method returns a string leading with zeros', function () {
    $value = 🙃()->number->integerLeadingZero(10);

    expect($value)->toMatch('/^^(0{0,10}[0-9]{0,10}){1}$/');
});

// endregion

// region integerNormal()

test('integerNormal() method returns an integer value', function () {
    $value = 🙃()->number->integerNormal();

    expect($value)->toBeInt();
});

test('integerNormal() method calculates integers with standard deviation', function () {
    $n = 10000;

    $values = [];
    foreach (range(1, 10000) as $k => $i) {
        $values[] = 🙃()->number->integerNormal(150, 100);
    }

    $mean = array_sum($values) / (float) $n;

    $variance = array_reduce(
            $values, static function ($variance, $item) use ($mean) {
                return $variance + ($item - $mean) ** 2;
            }, 0
        ) / (float) ($n - 1);

    $std_dev = sqrt($variance);

    expect($mean)->toEqualWithDelta(150, 5);
    expect($std_dev)->toEqualWithDelta(100, 3);
});

// endregion

// region integerExcept()

test('integerExcept() method returns an integer value', function () {
    $value = 🙃()->number->integerExcept();

    expect($value)->toBeInt();
});

test('integerExcept() method returns an integer except the given integer', function () {
    $value = 🙃()->number->integerExcept(2, 1, 2);

    expect($value)->toBe(1);
});

test('integerExcept() method returns an integer except the given array of integers', function () {
    $value = 🙃()->number->integerExcept([1, 2, 3, 4], 1, 5);

    expect($value)->toBe(5);
});

test('integerExcept() method throws a RangeException if there are not enough integers', function () {
    🙃()->number->integerExcept([1, 2, 3, 4, 5], 1, 5);
})->throws(RangeException::class);

// endregion

// region digit()

test('digit() method returns an integer value', function () {
    $value = 🙃()->number->digit();

    expect($value)->toBeInt();
});

test('digit() method returns a digit', function () {
    $value = 🙃()->number->digit();

    expect($value)->toBeGreaterThanOrEqual(0);
    expect($value)->toBeLessThanOrEqual(9);
});

test('digit() method returns a digit for the given $base', function () {
    $valueBase2 = 🙃()->number->digit(2);

    expect($valueBase2)->toBeGreaterThanOrEqual(0);
    expect($valueBase2)->toBeLessThanOrEqual(2);

    $base = random_int(2, 99);
    $value = 🙃()->number->digit($base);

    expect($value)->toBeGreaterThanOrEqual(0);
    expect($value)->toBeLessThanOrEqual($base);
});

// endregion

// region digitExcept()

test('digitExcept() method returns an integer value', function () {
    $value = 🙃()->number->digitExcept();

    expect($value)->toBeInt();
});

test('digitExcept() method returns a digit except given digit', function () {
    $value = 🙃()->number->digitExcept(1, 2);
    expect($value)->not()->toBe(1);

    $value = 🙃()->number->digitExcept(0, 2);
    expect($value)->not()->toBe(0);

    $value = 🙃()->number->digitExcept(1, 2);
    expect($value)->not()->toBe(1);
});

// endregion

// region digitNonZero()

test('digitNonZero() method returns an integer value', function () {
    $value = 🙃()->number->digitNonZero();

    expect($value)->toBeInt();
});

test('digitNonZero() method returns a digit that is not zero', function () {
    $value = 🙃()->number->digitNonZero(2);
    expect($value)->toBe(1);

    $value = 🙃()->number->digitNonZero();
    expect($value)->not()->toBe(0);
});

// endregion

// region floatBetween()

test('floatBetween() method returns a float value', function () {
    $value = 🙃()->number->floatBetween();

    expect($value)->toBeFloat();
});

test('floatBetween() method returns a float between $min and $max', function () {
    $value = 🙃()->number->floatBetween(0.0, 1.0);

    expect($value)->toBeGreaterThanOrEqual(0);
    expect($value)->toBeLessThanOrEqual(1);
});

test('floatBetween() method returns a float with given $precision', function () {
    $precision = random_int(0, 14);
    $value = 🙃()->number->floatBetween(0.0, 1.0, $precision);

    expect(strlen($value))->toBeLessThanOrEqual($precision + 2);
});

// endregion

// region floatPositive()

test('floatPositive() method returns a float value', function () {
    $value = 🙃()->number->floatPositive();

    expect($value)->toBeFloat();
});

test('floatPositive() method returns a positive float', function () {
    $value = 🙃()->number->floatPositive();

    expect($value)->toBeGreaterThanOrEqual(0);
});

test('floatPositive() method returns zero if $max=0', function () {
    $value = 🙃()->number->floatPositive(0);

    expect($value)->toBe(0.0);
});

test('floatPositive() method returns a float with given $precision', function () {
    $precision = random_int(0, 14);
    $value = 🙃()->number->floatPositive(1, $precision);

    expect($precision)->toBeLessThanOrEqual(strlen($value));
});

// endregion

// region floatNegative()

test('floatNegative() method returns a float value', function () {
    $value = 🙃()->number->floatNegative();

    expect($value)->toBeFloat();
});

test('floatNegative() method returns a negative float', function () {
    $value = 🙃()->number->floatNegative();

    expect($value)->toBeLessThan(0);
});

test('floatNegative() method returns a float with given $precision', function () {
    $precision = random_int(0, 14);
    $value = 🙃()->number->floatNegative(-1, $precision);

    expect(strlen($value))->toBeLessThanOrEqual($precision + 3);
});

// endregion

// region float()

test('float() method returns a float value', function () {
    $value = 🙃()->number->float();

    expect($value)->toBeFloat();
});

test('float() method left digit can be strictly set', function () {
    $leftDigits = random_int(1, 10);
    $value = 🙃()->number->float($leftDigits, 0, true);

    expect(strlen($value))->toBe($leftDigits);
});

test('float() method right digit can be strictly set', function () {
    $rightDigits = random_int(1, 14);
    $value = 🙃()->number->float(1, $rightDigits, true);

    expect(strlen($value))->toBeLessThanOrEqual($rightDigits + 2);
});

// endregion

// region floatNormal()

test('floatNormal() method returns a float', function () {
    $value = 🙃()->number->floatNormal();

    expect($value)->toBeFloat();
});

test('floatNormal() method calculates floats with standard deviation', function () {
    $n = 10000;

    $values = [];
    foreach (range(1, 10000) as $k => $i) {
        $values[] = 🙃()->number->floatNormal(150.0, 100.0);
    }

    $mean = array_sum($values) / (float) $n;

    $variance = array_reduce(
        $values, static fn ($variance, $item) => $variance + ($item - $mean) ** 2, 0
        ) / (float) ($n - 1);

    $std_dev = sqrt($variance);

    expect($mean)->toEqualWithDelta(150, 5);
    expect($std_dev)->toEqualWithDelta(100, 3);
});

// endregion

// region possibleIntegersCount()

test('possibleIntegersCount() method', function (int $min, int $max, int $expected) {
    $possibilities = callPrivateMethod(🙃()->number, 'possibleIntegersCount', $min, $max);

    expect($possibilities)->toBe($expected);
})->with([
    [1, 5, 5],
    [0, 5, 6],
    [-5, 5, 11],
    [-5, 0, 6],
    [-10, -5, 6],
    [0, 0, 1],
    [1, 1, 1],
]);

test('possibleIntegersCount() method swaps $min and $max if necessary', function () {
    $possibilities = callPrivateMethod(🙃()->number, 'possibleIntegersCount', 5, 1);

    expect($possibilities)->toBe(5);
});

// endregion
