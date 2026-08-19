<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Utils;

use Symfony\Component\Console\Exception\InvalidArgumentException;

use function Symfony\Component\String\u;

/**
 * This class is used to provide an example of integrating simple classes as
 * services into a Symfony application.
 * See https://symfony.com/doc/current/service_container.html#creating-configuring-services-in-the-container.
 *
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
final class Validator
{
    public function validateUsername(?string $username): string
    {
		error_log(sprintf('>>> Entering validateUsername($username=%s)', is_scalar($username) ? $username : gettype($username)));
        if (empty($username)) {
			error_log(sprintf('Throwing InvalidArgumentException from validateUsername($username=%s)', is_scalar($username) ? $username : gettype($username)));
            throw new InvalidArgumentException('The username can not be empty.');
        }

        if (1 !== preg_match('/^[a-z_]+$/', $username)) {
		error_log(sprintf('<<< Exiting validateUsername($username=%s)', is_scalar($username) ? $username : gettype($username)));
            throw new InvalidArgumentException('The username must contain only lowercase latin characters and underscores.');
        }

        return $username;
    }

    public function validatePassword(?string $plainPassword): string
    {
        if (empty($plainPassword)) {
            throw new InvalidArgumentException('The password can not be empty.');
        }

        if (u($plainPassword)->trim()->length() < 6) {
            throw new InvalidArgumentException('The password must be at least 6 characters long.');
        }

        return $plainPassword;
    }

    public function validateEmail(?string $email): string
    {
		error_log(sprintf('>>> Entering validateEmail($email=%s)', is_scalar($email) ? $email : gettype($email)));
        if (empty($email)) {
			error_log(sprintf('Throwing InvalidArgumentException from validateEmail($email=%s)', is_scalar($email) ? $email : gettype($email)));
            throw new InvalidArgumentException('The email can not be empty.');
        }

        if (null === u($email)->indexOf('@')) {
		error_log(sprintf('<<< Exiting validateEmail($email=%s)', is_scalar($email) ? $email : gettype($email)));
            throw new InvalidArgumentException('The email should look like a real email.');
        }

        return $email;
    }

    public function validateFullName(?string $fullName): string
    {
		error_log(sprintf('>>> Entering validateFullName($fullName=%s)', is_scalar($fullName) ? $fullName : gettype($fullName)));
        if (empty($fullName)) {
			error_log(sprintf('Throwing InvalidArgumentException from validateFullName($fullName=%s)', is_scalar($fullName) ? $fullName : gettype($fullName)));
		error_log(sprintf('<<< Exiting validateFullName($fullName=%s)', is_scalar($fullName) ? $fullName : gettype($fullName)));
            throw new InvalidArgumentException('The full name can not be empty.');
        }

        return $fullName;
    }
}
