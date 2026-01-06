<?php

namespace App\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EntityCountExtension extends AbstractExtension
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('count_entities', [$this, 'countEntities']),
        ];
    }

    public function countEntities(string $entityClass): int
    {
        return $this->em->getRepository($entityClass)->count([]);
    }
}
