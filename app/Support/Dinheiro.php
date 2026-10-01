<?php

namespace App\Support;

final class Dinheiro
{
    public static function centavos(string|int|float|null $valor): int
    {
        return (int) round(((float) ($valor ?? 0)) * 100);
    }

    public static function formatar(int $centavos): string
    {
        return number_format($centavos / 100, 2, '.', '');
    }
}