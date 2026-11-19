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

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Defines the properties of the Post entity to represent the blog posts.
 *
 * See https://symfony.com/doc/current/doctrine.html#creating-an-entity-class
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(name: 'symfony_demo_post')]
#[UniqueEntity(fields: ['slug'], errorPath: 'title', message: 'post.slug_unique')]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank(message: 'post.blank_summary')]
    #[Assert\Length(max: 255)]
    private ?string $summary = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'post.blank_content')]
    #[Assert\Length(min: 10, minMessage: 'post.too_short_content')]
    private ?string $content = null;

    #[ORM\Column]
    private \DateTimeImmutable $publishedAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'post', orphanRemoval: true, cascade: ['persist'])]
    #[ORM\OrderBy(['publishedAt' => 'DESC'])]
    private Collection $comments;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, cascade: ['persist'])]
    #[ORM\JoinTable(name: 'symfony_demo_post_tag')]
    #[ORM\OrderBy(['name' => 'ASC'])]
    #[Assert\Count(max: 4, maxMessage: 'post.too_many_tags')]
    private Collection $tags;

    public function __construct()
    {
		error_log(sprintf('[ERROR] >>> Entering __construct()'));
        $this->publishedAt = new \DateTimeImmutable();
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
		error_log(sprintf('[WARN] <<< Exiting __construct()'));
    }

    public function getId(): ?int
    {
		error_log(sprintf('[ERROR] >>> Entering getId()'));
        error_log(sprintf('[WARN] <<< Exiting getId()'));
        return $this->id;
    }

    public function getTitle(): ?string
    {
		error_log(sprintf('[ERROR] >>> Entering getTitle()'));
        error_log(sprintf('[WARN] <<< Exiting getTitle()'));
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
		error_log(sprintf('[ERROR] >>> Entering setTitle($title=%s)', is_scalar($title) ? $title : gettype($title)));
        $this->title = $title;
		error_log(sprintf('[WARN] <<< Exiting setTitle($title=%s)', is_scalar($title) ? $title : gettype($title)));
    }

    public function getSlug(): ?string
    {
		error_log(sprintf('[ERROR] >>> Entering getSlug()'));
        error_log(sprintf('[WARN] <<< Exiting getSlug()'));
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
		error_log(sprintf('[ERROR] >>> Entering setSlug($slug=%s)', is_scalar($slug) ? $slug : gettype($slug)));
        $this->slug = $slug;
		error_log(sprintf('[WARN] <<< Exiting setSlug($slug=%s)', is_scalar($slug) ? $slug : gettype($slug)));
    }

    public function getContent(): ?string
    {
		error_log(sprintf('[ERROR] >>> Entering getContent()'));
        error_log(sprintf('[WARN] <<< Exiting getContent()'));
        return $this->content;
    }

    public function setContent(?string $content): void
    {
		error_log(sprintf('[ERROR] >>> Entering setContent($content=%s)', is_scalar($content) ? $content : gettype($content)));
        $this->content = $content;
		error_log(sprintf('[WARN] <<< Exiting setContent($content=%s)', is_scalar($content) ? $content : gettype($content)));
    }

    public function getPublishedAt(): \DateTimeImmutable
    {
		error_log(sprintf('[ERROR] >>> Entering getPublishedAt()'));
        error_log(sprintf('[WARN] <<< Exiting getPublishedAt()'));
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): void
    {
		error_log(sprintf('[ERROR] >>> Entering setPublishedAt($publishedAt=%s)', is_scalar($publishedAt) ? $publishedAt : gettype($publishedAt)));
        $this->publishedAt = $publishedAt;
		error_log(sprintf('[WARN] <<< Exiting setPublishedAt($publishedAt=%s)', is_scalar($publishedAt) ? $publishedAt : gettype($publishedAt)));
    }

    public function getAuthor(): ?User
    {
		error_log(sprintf('[ERROR] >>> Entering getAuthor()'));
        error_log(sprintf('[WARN] <<< Exiting getAuthor()'));
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
		error_log(sprintf('[ERROR] >>> Entering setAuthor($author=%s)', is_scalar($author) ? $author : gettype($author)));
        $this->author = $author;
		error_log(sprintf('[WARN] <<< Exiting setAuthor($author=%s)', is_scalar($author) ? $author : gettype($author)));
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
		error_log(sprintf('[ERROR] >>> Entering getComments()'));
        error_log(sprintf('[WARN] <<< Exiting getComments()'));
        return $this->comments;
    }

    public function addComment(Comment $comment): void
    {
		error_log(sprintf('[ERROR] >>> Entering addComment($comment=%s)', is_scalar($comment) ? $comment : gettype($comment)));
        $comment->setPost($this);

        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
        }
		error_log(sprintf('[WARN] <<< Exiting addComment($comment=%s)', is_scalar($comment) ? $comment : gettype($comment)));
    }

    public function removeComment(Comment $comment): void
    {
		error_log(sprintf('[ERROR] >>> Entering removeComment($comment=%s)', is_scalar($comment) ? $comment : gettype($comment)));
        $this->comments->removeElement($comment);
		error_log(sprintf('[WARN] <<< Exiting removeComment($comment=%s)', is_scalar($comment) ? $comment : gettype($comment)));
    }

    public function getSummary(): ?string
    {
		error_log(sprintf('[ERROR] >>> Entering getSummary()'));
        error_log(sprintf('[WARN] <<< Exiting getSummary()'));
        return $this->summary;
    }

    public function setSummary(?string $summary): void
    {
		error_log(sprintf('[ERROR] >>> Entering setSummary($summary=%s)', is_scalar($summary) ? $summary : gettype($summary)));
        $this->summary = $summary;
		error_log(sprintf('[WARN] <<< Exiting setSummary($summary=%s)', is_scalar($summary) ? $summary : gettype($summary)));
    }

    public function addTag(Tag ...$tags): void
    {
		error_log(sprintf('[ERROR] >>> Entering addTag()'));
        foreach ($tags as $tag) {
            if (!$this->tags->contains($tag)) {
                $this->tags->add($tag);
            }
        }
		error_log(sprintf('[WARN] <<< Exiting addTag()'));
    }

    public function removeTag(Tag $tag): void
    {
		error_log(sprintf('[ERROR] >>> Entering removeTag($tag=%s)', is_scalar($tag) ? $tag : gettype($tag)));
        $this->tags->removeElement($tag);
		error_log(sprintf('[WARN] <<< Exiting removeTag($tag=%s)', is_scalar($tag) ? $tag : gettype($tag)));
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
		error_log(sprintf('[ERROR] >>> Entering getTags()'));
        error_log(sprintf('[WARN] <<< Exiting getTags()'));
        return $this->tags;
    }
}
