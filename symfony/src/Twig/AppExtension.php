<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('yesNo', [$this, 'formatBoolean']),
        ];
    }

    public function formatBoolean(bool $value): string
    {
        if ($value) {
            return 'Yes';
        } else {
            return 'No';
        }
    }
}