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

    // méthode pour avoir les mois en francais
    private function frenchDate(\DateTimeInterface $date, bool $withYear): string
    {
        $months = [
            1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril',
            5 => 'mai', 6 => 'juin', 7 => 'juillet', 8 => 'août',
            9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre',
        ];

        $day = $date->format('j');
        $month = $months[(int) $date->format('n')];

        if ($withYear) {
            return sprintf('%s %s %s', $day, $month, $date->format('Y'));
        }

        return sprintf('%s %s', $day, $month);
    }
}
