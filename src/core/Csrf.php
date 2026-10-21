<?php

declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    private const KEY = '_csrf';

    public static function token(): string
    {
        $token = Session::get(self::KEY);
        if (!is_string($token) || strlen($token) !== 64) {
            $token = self::rotate();
        }

        return $token;
    }

    public static function rotate(): string
    {
        $token = bin2hex(random_bytes(32));
        Session::set(self::KEY, $token);

        return $token;
    }

    public static function verify(string $submitted): void
    {
        $token = Session::get(self::KEY);

        if (!is_string($token) || $submitted === '' || !hash_equals($token, $submitted)) {
            throw new HttpException(419);
        }
    }
}
