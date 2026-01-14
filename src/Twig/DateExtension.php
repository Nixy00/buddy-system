<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DateExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('buddyduration', [$this, 'formatDuration']),
            new TwigFilter('buddysmartdate', [$this, 'formatSmartDate']),
        ];
    }

}
