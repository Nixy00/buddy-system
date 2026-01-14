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

    // l'heure dans la bulle de message (ex: "14:30" ou "Il y a 2 min")
    public function formatDuration(\DateTimeInterface $date): string
    {
        $now = new \DateTime();
        $diff = $now->getTimestamp() - $date->getTimestamp();

        if ($diff < 60) {
            return "À l'instant";
        }

        if ($diff < 3600) {
            $minutes = floor($diff / 60);

            return "Il y a {$minutes} min";
        }

        return $date->format('H:i');
    }

    // le séparateur central (ex: "Aujourd'hui", "Hier", "3 octobre 2024")
    public function formatSmartDate(\DateTimeInterface $date): string
    {
        $now = new \DateTime();
        $dateDay = $date->format('Y-m-d');
        $nowDay = $now->format('Y-m-d');

        $yesterday = (clone $now)->modify('-1 day')->format('Y-m-d');

        if ($dateDay === $nowDay) {
            return "Aujourd'hui";
        }

        if ($dateDay === $yesterday) {
            return 'Hier';
        }

        if ($date->format('Y') === $now->format('Y')) {
            return $this->frenchDate($date, false);
        }

        return $this->frenchDate($date, true);
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
