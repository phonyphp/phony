<?php

use SRL\Builder;
use Phonyland\Phony;

function 🙃(string $locale = 'en'): Phony
{
    return new Phony($locale);
}

function regex(): Builder
{
    return \SRL\SRL::unicode();
}

function assertRulesMatching(Builder $rules, string $value)
{
    expect($rules->isMatching($value))->toBeTrue();
}

function callPrivateFakeMethod($name, ...$args)
{
    return callPrivateMethod(🙃()->alphabet, $name, ...$args);
}

function callPrivateMethod($instance, $name, ...$args)
{
    $method = new ReflectionMethod($instance, $name);
    $method->setAccessible(true);

    return $method->invokeArgs($instance, $args);
}
