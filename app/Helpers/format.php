<?php
declare(strict_types=1);

/** Format helpers — currency, dates, numbers (Arabic display). */
function money(mixed $amount): string
{
    return number_format((float)($amount ?? 0), 0, '.', ',') . ' ر.ي';
}

function int_num(mixed $value): string
{
    return number_format((float)($value ?? 0), 0, '.', ',');
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
