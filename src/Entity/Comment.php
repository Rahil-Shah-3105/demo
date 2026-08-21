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
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\String\u;

/**
 * Defines the properties of the Comment entity to represent the blog comments.
 *
 * See https://symfony.com/doc/current/doctrine.html#creating-an-entity-class
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
#[ORM\Entity]
#[ORM\Table(name: 'symfony_demo_comment')]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'comment.blank')]
    #[Assert\Length(min: 5, max: 10000, minMessage: 'comment.too_short', maxMessage: 'comment.too_long')]
    private ?string $content = null;

    #[ORM\Column]
    private \DateTimeImmutable $publishedAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    public function __construct()
    {
		error_log(sprintf('>>> Entering __construct()'));
        $this->publishedAt = new \DateTimeImmutable();
		error_log(sprintf('<<< Exiting __construct()'));
    }

    #[Assert\IsTrue(message: 'comment.is_spam')]
    public function isLegitComment(): bool
    {
		error_log(sprintf('>>> Entering isLegitComment()'));
        $containsInvalidCharacters = null !== u($this->content)->indexOf('@');

		error_log(sprintf('<<< Exiting isLegitComment()'));
        return !$containsInvalidCharacters;
    }

    public function getId(): ?int
    {
		error_log(sprintf('>>> Entering getId()'));
        return $this->id;
    }

    public function getContent(): ?string
    {
		error_log(sprintf('>>> Entering getContent()'));
        return $this->content;
    }

    public function setContent(string $content): void
    {
		error_log(sprintf('>>> Entering setContent($content=%s)', is_scalar($content) ? $content : gettype($content)));
        $this->content = $content;
		error_log(sprintf('<<< Exiting setContent($content=%s)', is_scalar($content) ? $content : gettype($content)));
    }

    public function getPublishedAt(): \DateTimeImmutable
    {
		error_log(sprintf('>>> Entering getPublishedAt()'));
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): void
    {
		error_log(sprintf('>>> Entering setPublishedAt($publishedAt=%s)', is_scalar($publishedAt) ? $publishedAt : gettype($publishedAt)));
        $this->publishedAt = $publishedAt;
		error_log(sprintf('<<< Exiting setPublishedAt($publishedAt=%s)', is_scalar($publishedAt) ? $publishedAt : gettype($publishedAt)));
    }

    public function getAuthor(): ?User
    {
		error_log(sprintf('>>> Entering getAuthor()'));
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
		error_log(sprintf('>>> Entering setAuthor($author=%s)', is_scalar($author) ? $author : gettype($author)));
        $this->author = $author;
		error_log(sprintf('<<< Exiting setAuthor($author=%s)', is_scalar($author) ? $author : gettype($author)));
    }

    public function getPost(): ?Post
    {
		error_log(sprintf('>>> Entering getPost()'));
        return $this->post;
    }

    public function setPost(Post $post): void
    {
		error_log(sprintf('>>> Entering setPost($post=%s)', is_scalar($post) ? $post : gettype($post)));
        $this->post = $post;
		error_log(sprintf('<<< Exiting setPost($post=%s)', is_scalar($post) ? $post : gettype($post)));
    }
}
