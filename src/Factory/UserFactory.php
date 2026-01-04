<?php

namespace App\Factory;

use App\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{
    private \Transliterator $transliterator;

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        $this->transliterator = \Transliterator::create('Any-Lower; Latin-ASCII');
        parent::__construct();
    }

    public static function class(): string
    {
        return User::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        $firstname = self::faker()->firstName();
        $lastname = self::faker()->lastName();

        $emailFirstName = $this->normalizeName($firstname);
        $emailLastName = $this->normalizeName($lastname);

        // Transformation prénom.nom en minuscules et sans accents
        $emailLocalPart = transliterator_transliterate(
            'Any-Lower; Latin-ASCII',
            $emailFirstName.'.'.$emailLastName
        );

        return [
            'dateCrea' => self::faker()->dateTimeBetween('-2 years', 'now'),
            'email' => $emailLocalPart.'@'.self::faker()->freeEmailDomain(),
            'login' => self::faker()->unique()->userName,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this->afterInstantiate(function (User $user): void {
            $user->setMdpHache(password_hash('test', PASSWORD_BCRYPT));
        });
    }

    /**
     * Normalise un nom/prénom pour l'utiliser dans une adresse email :
     *  - minuscules
     *  - sans accents
     *  - remplace tout caractère non alphabétique par un tiret
     */
    protected function normalizeName(string $name): string
    {
        // mise en minuscules et retrait des accents
        $normalized = $this->transliterator->transliterate($name);

        // remplace tout caractère non alphabétique par un tiret
        $normalized = preg_replace('/[^a-z]+/', '-', $normalized);

        // nettoie les tirets multiples au début et à la fin du nom
        $normalized = trim($normalized, '-');

        return $normalized;
    }
}
