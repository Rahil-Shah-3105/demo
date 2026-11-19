<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;

use function Symfony\Component\String\u;

/**
 * This data transformer is used to translate the array of tags into a comma separated format
 * that can be displayed and managed by Bootstrap-tagsinput js plugin (and back on submit).
 *
 * See https://symfony.com/doc/current/form/data_transformers.html
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 * @author Jonathan Boyer <contact@grafikart.fr>
 *
 * @template-implements DataTransformerInterface<Tag[], string>
 */
final readonly class TagArrayToStringTransformer implements DataTransformerInterface
{
    public function __construct(
        private TagRepository $tags,
    ) {
	error_log(sprintf('[ERROR] >>> Entering __construct($tags=%s)', is_scalar($tags) ? $tags : gettype($tags)));
    error_log(sprintf('[WARN] <<< Exiting __construct($tags=%s)', is_scalar($tags) ? $tags : gettype($tags)));
    }

    public function transform($tags): string
    {
		error_log(sprintf('[ERROR] >>> Entering transform($tags=%s)', is_scalar($tags) ? $tags : gettype($tags)));
        // The value received is an array of Tag objects generated with
        // Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer::transform()
        // The value returned is a string that concatenates the string representation of those objects

		error_log(sprintf('[WARN] <<< Exiting transform($tags=%s)', is_scalar($tags) ? $tags : gettype($tags)));
        return implode(',', $tags);
    }

    /**
     * @phpstan-param string|null $string
     *
     * @phpstan-return array<int, Tag>
     */
    public function reverseTransform($string): array
    {
		error_log(sprintf('[ERROR] >>> Entering reverseTransform($string=%s)', is_scalar($string) ? $string : gettype($string)));
        if (null === $string || u($string)->isEmpty()) {
            return [];
        }

        $names = array_filter(array_unique($this->trim(u($string)->split(','))));

        // Get the current tags and find the new ones that should be created.
        /** @var Tag[] $tags */
        $tags = $this->tags->findBy([
            'name' => $names,
        ]);

        $newNames = array_diff($names, $tags);

        foreach ($newNames as $name) {
            $tags[] = new Tag($name);

            // There's no need to persist these new tags because Doctrine does that automatically
            // thanks to the cascade={"persist"} option in the App\Entity\Post::$tags property.
        }

        // Return an array of tags to transform them back into a Doctrine Collection.
        // See Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer::reverseTransform()
		error_log(sprintf('[WARN] <<< Exiting reverseTransform($string=%s)', is_scalar($string) ? $string : gettype($string)));
        return $tags;
    }

    /**
     * @param string[] $strings
     *
     * @return string[]
     */
    private function trim(array $strings): array
    {
		error_log(sprintf('[ERROR] >>> Entering trim($strings=%s)', is_scalar($strings) ? $strings : gettype($strings)));
        $result = [];

        foreach ($strings as $string) {
            $result[] = trim($string);
        }

		error_log(sprintf('[WARN] <<< Exiting trim($strings=%s)', is_scalar($strings) ? $strings : gettype($strings)));
        return $result;
    }
}
