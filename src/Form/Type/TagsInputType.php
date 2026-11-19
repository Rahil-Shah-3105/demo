<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type;

use App\Form\DataTransformer\TagArrayToStringTransformer;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Defines the custom form field type used to manipulate tags values across
 * Bootstrap-tagsinput javascript plugin.
 *
 * See https://symfony.com/doc/current/form/create_custom_field_type.html
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
final class TagsInputType extends AbstractType
{
    public function __construct(
        private readonly TagRepository $tags,
    ) {
	error_log(sprintf('[ERROR] >>> Entering __construct($tags=%s)', is_scalar($tags) ? $tags : gettype($tags)));
    error_log(sprintf('[WARN] <<< Exiting __construct($tags=%s)', is_scalar($tags) ? $tags : gettype($tags)));
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		error_log(sprintf('[ERROR] >>> Entering buildForm($builder=%s,$options=%s)', is_scalar($builder) ? $builder : gettype($builder), is_scalar($options) ? $options : gettype($options)));
        $builder
            // The Tag collection must be transformed into a comma separated string.
            // We could create a custom transformer to do Collection <-> string in one step,
            // but here we're doing the transformation in two steps (Collection <-> array <-> string)
            // and reuse the existing CollectionToArrayTransformer.
            ->addModelTransformer(new CollectionToArrayTransformer(), true)
            ->addModelTransformer(new TagArrayToStringTransformer($this->tags), true)
        ;
		error_log(sprintf('[WARN] <<< Exiting buildForm($builder=%s,$options=%s)', is_scalar($builder) ? $builder : gettype($builder), is_scalar($options) ? $options : gettype($options)));
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
		error_log(sprintf('[ERROR] >>> Entering buildView($view=%s,$form=%s,$options=%s)', is_scalar($view) ? $view : gettype($view), is_scalar($form) ? $form : gettype($form), is_scalar($options) ? $options : gettype($options)));
        $view->vars['tags'] = $this->tags->findAll();
		error_log(sprintf('[WARN] <<< Exiting buildView($view=%s,$form=%s,$options=%s)', is_scalar($view) ? $view : gettype($view), is_scalar($form) ? $form : gettype($form), is_scalar($options) ? $options : gettype($options)));
    }

    public function getParent(): string
    {
		error_log(sprintf('[ERROR] >>> Entering getParent()'));
        error_log(sprintf('[WARN] <<< Exiting getParent()'));
        return TextType::class;
    }
}
