<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Defines the properties of the User entity to represent the application users.
 *
 * See https://symfony.com/doc/current/doctrine.html#creating-an-entity-class
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'symfony_demo_user')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // We can use constants for roles to find usages all over the application rather
    // than doing a full-text search on the "ROLE_" string.
    // It also prevents from making typo errors.
    final public const ROLE_USER = 'ROLE_USER';
    final public const ROLE_ADMIN = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private ?string $fullName = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $username = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $password = null;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    public function getId(): ?int
    {
		error_log(sprintf('[ERROR] >>> Entering getId()'));
        error_log(sprintf('[WARN] <<< Exiting getId()'));
        return $this->id;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getFullName(): ?string
    {
		error_log(sprintf('[ERROR] >>> Entering getFullName()'));
        error_log(sprintf('[WARN] <<< Exiting getFullName()'));
        return $this->fullName;
    }

    public function getUserIdentifier(): string
    {
		error_log(sprintf('[ERROR] >>> Entering getUserIdentifier()'));
        error_log(sprintf('[WARN] <<< Exiting getUserIdentifier()'));
        return (string) $this->username;
    }

    public function getUsername(): string
    {
		error_log(sprintf('[ERROR] >>> Entering getUsername()'));
        error_log(sprintf('[WARN] <<< Exiting getUsername()'));
        return $this->getUserIdentifier();
    }

    public function setUsername(string $username): void
    {
		error_log(sprintf('[ERROR] >>> Entering setUsername($username=%s)', is_scalar($username) ? $username : gettype($username)));
        $this->username = $username;
		error_log(sprintf('[WARN] <<< Exiting setUsername($username=%s)', is_scalar($username) ? $username : gettype($username)));
    }

    public function getEmail(): ?string
    {
		error_log(sprintf('[ERROR] >>> Entering getEmail()'));
        error_log(sprintf('[WARN] <<< Exiting getEmail()'));
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
		error_log(sprintf('[ERROR] >>> Entering getPassword()'));
        error_log(sprintf('[WARN] <<< Exiting getPassword()'));
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles(): array
    {
		error_log(sprintf('[ERROR] >>> Entering getRoles()'));
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = self::ROLE_USER;
        }

		error_log(sprintf('[WARN] <<< Exiting getRoles()'));
        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
		error_log(sprintf('[ERROR] >>> Entering setRoles($roles=%s)', is_scalar($roles) ? $roles : gettype($roles)));
        $this->roles = $roles;
		error_log(sprintf('[WARN] <<< Exiting setRoles($roles=%s)', is_scalar($roles) ? $roles : gettype($roles)));
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
		error_log(sprintf('[ERROR] >>> Entering eraseCredentials()'));
        // if you had a plainPassword property, you'd nullify it here
        // $this->plainPassword = null;
		error_log(sprintf('[WARN] <<< Exiting eraseCredentials()'));
    }

    /**
     * @return array{int|null, string|null, string|null}
     */
    public function __serialize(): array
    {
		error_log(sprintf('[ERROR] >>> Entering __serialize()'));
        error_log(sprintf('[WARN] <<< Exiting __serialize()'));
        return [$this->id, $this->username, $this->password];
    }

    /**
     * @param array{int|null, string, string} $data
     */
    public function __unserialize(array $data): void
    {
		error_log(sprintf('[ERROR] >>> Entering __unserialize($data=%s)', is_scalar($data) ? $data : gettype($data)));
        [$this->id, $this->username, $this->password] = $data;
		error_log(sprintf('[WARN] <<< Exiting __unserialize($data=%s)', is_scalar($data) ? $data : gettype($data)));
    }
}
