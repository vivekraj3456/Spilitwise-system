<?php

namespace App\Services;

use InvalidArgumentException;

class Money
{
    private static function formatIndianNumber(int $number): string
    {
        $digits = (string) $number;

        // Indian system: last 3 digits stay together, then groups of 2.
        if (strlen($digits) <= 3) {
            return $digits;
        }

        $last3 = substr($digits, -3);
        $rest = substr($digits, 0, -3);

        $parts = [];
        while (strlen($rest) > 2) {
            $parts[] = substr($rest, -2);
            $rest = substr($rest, 0, -2);
        }

        if ($rest !== '') {
            $parts[] = $rest;
        }

        $parts = array_reverse($parts);

        return implode(',', $parts).','.$last3;
    }

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

        $formattedWhole = self::formatIndianNumber($whole);
        if ($fraction === 0) {
            return $prefix.'₹'.$formattedWhole;
        }

        return $prefix.'₹'.$formattedWhole.'.'.str_pad((string) $fraction, 2, '0', STR_PAD_LEFT);
    }
}
