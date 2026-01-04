<?php

namespace App\Factory;

use App\Entity\Profil;
use Symfony\Component\Finder\Finder;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Profil>
 */
final class ProfilFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Profil::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     *
     * @throws \DateMalformedStringException
     */
    protected function defaults(): array|callable
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../../assets/avatars');

        $avatars = [];
        foreach ($finder as $file) {
            $avatars[] = file_get_contents($file->getRealPath());
        }

        $dateCrea = self::faker()->dateTimeBetween('-2 years', 'now');
        $dateModif = (clone $dateCrea);
        $dateModif->modify('+'.self::faker()->numberBetween(0, 60).' days');

        $localisations = [
            ['ville' => 'Reims', 'pays' => 'France'],
            ['ville' => 'Paris', 'pays' => 'France'],
            ['ville' => 'Lille', 'pays' => 'France'],
            ['ville' => 'Lyon', 'pays' => 'France'],
            ['ville' => 'Bruxelles', 'pays' => 'Belgique'],
            ['ville' => 'Genève', 'pays' => 'Suisse'],
            ['ville' => 'Madrid', 'pays' => 'Espagne'],
        ];

        $localisation = self::faker()->randomElement($localisations);

        $typeCompte = self::faker()->randomElement(['ETUDIANT', 'BUDDY']);

        $languesPool = ['Français', 'Anglais', 'Espagnol', 'Allemand', 'Italien', 'Arabe'];
        $langues = self::faker()->randomElements($languesPool, self::faker()->numberBetween(1, 3));
        sort($langues);

        $disponibilites = [
            'Semaine : 18h–22h',
            'Week-end : après-midi',
            'En semaine : midi',
            'Flexible (selon planning)',
            'Soirées principalement',
        ];

        $biographie = [
            'Étudiant(e) à l’URCA, je cherche à rencontrer du monde et à découvrir la ville.',
            'Nouveau/nouvelle à Reims, je suis partant(e) pour des sorties simples : café, balade, musée.',
            'Je suis en échange universitaire et j’aimerais pratiquer les langues et me faire des amis.',
        ];

        $bioBuddy = [
            'Buddy motivé(e) pour aider les nouveaux arrivants : bons plans, campus, démarches, sorties.',
            'J’aime accueillir les étudiants internationaux et organiser des activités (sport, culture, café).',
            'Disponible pour échanger et accompagner sur Reims : campus, ville, événements, vie étudiante.',
        ];

        return [
            'dateCrea' => $dateCrea,
            'dateModif' => $dateModif,
            'dateNaissance' => self::faker()->dateTimeBetween('-60 years', '-18 years'),
            'genre' => self::faker()->randomElement(['HOMME', 'FEMME']),
            'nom' => self::faker()->lastName(),
            'prenom' => self::faker()->firstName(),
            'typeCompte' => self::faker()->randomElement(['ETUDIANT', 'BUDDY']),
            'telephone' => self::faker()->numerify('06########'),
            'biographie' => ('BUDDY' === $typeCompte)
                ? self::faker()->randomElement($bioBuddy)
                : self::faker()->randomElement($biographie),
            'disponibilites' => self::faker()->randomElement($disponibilites),
            'langues' => implode(', ', $langues),
            'ville' => self::faker()->city(),
            'pays' => self::faker()->country(),
            'avatar' => $avatars ? self::faker()->randomElement($avatars) : null,
            'utilisateur' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Profil $profil): void {})
        ;
    }
}
