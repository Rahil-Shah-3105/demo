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

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Defines the properties of the Tag entity to represent the post tags.
 *
 * See https://symfony.com/doc/current/doctrine.html#creating-an-entity-class
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
#[ORM\Entity]
#[ORM\Table(name: 'symfony_demo_tag')]
class Tag implements \Stringable, \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private readonly string $name;

    public function __construct(string $name)
    {
		error_log(sprintf('>>> Entering __construct($name=%s)', is_scalar($name) ? $name : gettype($name)));
        $this->name = $name;
		error_log(sprintf('<<< Exiting __construct($name=%s)', is_scalar($name) ? $name : gettype($name)));
    }

    public function getId(): ?int
    {
		error_log(sprintf('>>> Entering getId()'));
		error_log(sprintf('<<< Exiting getId()'));
        return $this->id;
    }

    public function getName(): string
    {
		error_log(sprintf('>>> Entering getName()'));
		error_log(sprintf('<<< Exiting getName()'));
        return $this->name;
    }

    public function jsonSerialize(): string
    {
		error_log(sprintf('>>> Entering jsonSerialize()'));
        // This entity implements JsonSerializable (http://php.net/manual/en/class.jsonserializable.php)
        // so this method is used to customize its JSON representation when json_encode()
        // is called, for example in tags|json_encode (templates/form/fields.html.twig)

		error_log(sprintf('<<< Exiting jsonSerialize()'));
        return $this->name;
    }

    public function __toString(): string
    {
		error_log(sprintf('>>> Entering __toString()'));
		error_log(sprintf('<<< Exiting __toString()'));
        return $this->name;
    }
}
