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
		error_log(sprintf('>>> Entering getId()'));
		error_log(sprintf('<<< Exiting getId()'));
        return $this->id;
    }

    public function setFullName(string $fullName): void
    {
		error_log(sprintf('>>> Entering setFullName($fullName=%s)', is_scalar($fullName) ? $fullName : gettype($fullName)));
        $this->fullName = $fullName;
		error_log(sprintf('<<< Exiting setFullName($fullName=%s)', is_scalar($fullName) ? $fullName : gettype($fullName)));
    }

    public function getFullName(): ?string
    {
		error_log(sprintf('>>> Entering getFullName()'));
		error_log(sprintf('<<< Exiting getFullName()'));
        return $this->fullName;
    }

    public function getUserIdentifier(): string
    {
		error_log(sprintf('>>> Entering getUserIdentifier()'));
		error_log(sprintf('<<< Exiting getUserIdentifier()'));
        return (string) $this->username;
    }

    public function getUsername(): string
    {
		error_log(sprintf('>>> Entering getUsername()'));
		error_log(sprintf('<<< Exiting getUsername()'));
        return $this->getUserIdentifier();
    }

    public function setUsername(string $username): void
    {
		error_log(sprintf('>>> Entering setUsername($username=%s)', is_scalar($username) ? $username : gettype($username)));
        $this->username = $username;
		error_log(sprintf('<<< Exiting setUsername($username=%s)', is_scalar($username) ? $username : gettype($username)));
    }

    public function getEmail(): ?string
    {
		error_log(sprintf('>>> Entering getEmail()'));
		error_log(sprintf('<<< Exiting getEmail()'));
        return $this->email;
    }

    public function setEmail(string $email): void
    {
		error_log(sprintf('>>> Entering setEmail($email=%s)', is_scalar($email) ? $email : gettype($email)));
        $this->email = $email;
		error_log(sprintf('<<< Exiting setEmail($email=%s)', is_scalar($email) ? $email : gettype($email)));
    }

    public function getPassword(): ?string
    {
		error_log(sprintf('>>> Entering getPassword()'));
		error_log(sprintf('<<< Exiting getPassword()'));
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
		error_log(sprintf('>>> Entering getRoles()'));
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = self::ROLE_USER;
        }

		error_log(sprintf('<<< Exiting getRoles()'));
        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
		error_log(sprintf('>>> Entering setRoles($roles=%s)', is_scalar($roles) ? $roles : gettype($roles)));
        $this->roles = $roles;
		error_log(sprintf('<<< Exiting setRoles($roles=%s)', is_scalar($roles) ? $roles : gettype($roles)));
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
		error_log(sprintf('>>> Entering eraseCredentials()'));
        // if you had a plainPassword property, you'd nullify it here
        // $this->plainPassword = null;
		error_log(sprintf('<<< Exiting eraseCredentials()'));
    }

    /**
     * @return array{int|null, string|null, string|null}
     */
    public function __serialize(): array
    {
		error_log(sprintf('>>> Entering __serialize()'));
		error_log(sprintf('<<< Exiting __serialize()'));
        return [$this->id, $this->username, $this->password];
    }

    /**
     * @param array{int|null, string, string} $data
     */
    public function __unserialize(array $data): void
    {
		error_log(sprintf('>>> Entering __unserialize($data=%s)', is_scalar($data) ? $data : gettype($data)));
        [$this->id, $this->username, $this->password] = $data;
		error_log(sprintf('<<< Exiting __unserialize($data=%s)', is_scalar($data) ? $data : gettype($data)));
    }
}
