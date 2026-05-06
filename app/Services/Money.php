<?php

namespace App\Services;

use InvalidArgumentException;

class Money
{
    public static function parseToCents(string $amount): int
    {
        $normalized = trim(str_replace(',', '', $amount));

        if (! preg_match('/^\d+(\.\d{1,2})?$/', $normalized)) {
            throw new InvalidArgumentException('Amount must be a valid money value.');
        }

        [$whole, $fraction] = array_pad(explode('.', $normalized, 2), 2, '0');
        $fraction = str_pad($fraction, 2, '0');

        return ((int) $whole * 100) + (int) $fraction;
    }

    public static function formatCents(int $amountCents): string
    {
        $prefix = $amountCents < 0 ? '-' : '';
        $absolute = abs($amountCents);
        $whole = intdiv($absolute, 100);
        $fraction = $absolute % 100;

        return $prefix.'$'.number_format($whole).'.'.str_pad((string) $fraction, 2, '0', STR_PAD_LEFT);
    }
}
