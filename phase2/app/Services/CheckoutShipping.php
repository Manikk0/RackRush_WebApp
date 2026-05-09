<?php

namespace App\Services;

// Fixed delivery options and fees for checkout (cart estimate + order placement).
class CheckoutShipping
{
    /** @var array<string, array{label: string, fee: float}> */
    private const METHODS = [
        'pickup' => ['label' => 'Osobný odber na prevádzke', 'fee' => 0.0],
        'standard' => ['label' => 'Kuriér – doručenie do 2 prac. dní', 'fee' => 4.99],
        'express' => ['label' => 'Kuriér – expres (nasledujúci prac. deň)', 'fee' => 9.99],
    ];

    private const DEFAULT_METHOD = 'standard';

    /** @return array<string, array{label: string, fee: float}> */
    public static function methods(): array
    {
        return self::METHODS;
    }

    /** Map method key => fee (for JS on checkout page). */
    public static function feesMap(): array
    {
        $out = [];
        foreach (self::METHODS as $key => $meta) {
            $out[$key] = $meta['fee'];
        }

        return $out;
    }

    public static function isValid(string $method): bool
    {
        return isset(self::METHODS[$method]);
    }

    public static function fee(string $method): float
    {
        if (! self::isValid($method)) {
            return self::METHODS[self::DEFAULT_METHOD]['fee'];
        }

        return self::METHODS[$method]['fee'];
    }

    public static function label(string $method): string
    {
        if (! self::isValid($method)) {
            return self::METHODS[self::DEFAULT_METHOD]['label'];
        }

        return self::METHODS[$method]['label'];
    }

    public static function defaultMethod(): string
    {
        return self::DEFAULT_METHOD;
    }
}
