<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form;

use App\Entity\Post;
use App\Form\Type\DateTimePickerType;
use App\Form\Type\TagsInputType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Defines the form used to create and manipulate blog posts.
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
final class PostType extends AbstractType
{
    // Form types are services, so you can inject other services in them if needed
    public function __construct(
        private readonly SluggerInterface $slugger,
    ) {
	error_log(sprintf('[ERROR] >>> Entering __construct($slugger=%s)', is_scalar($slugger) ? $slugger : gettype($slugger)));
    error_log(sprintf('[WARN] <<< Exiting __construct($slugger=%s)', is_scalar($slugger) ? $slugger : gettype($slugger)));
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		error_log(sprintf('[ERROR] >>> Entering buildForm($builder=%s,$options=%s)', is_scalar($builder) ? $builder : gettype($builder), is_scalar($options) ? $options : gettype($options)));
        // For the full reference of options defined by each form field type
        // see https://symfony.com/doc/current/reference/forms/types.html

        // By default, form fields include the 'required' attribute, which enables
        // the client-side form validation. This means that you can't test the
        // server-side validation errors from the browser. To temporarily disable
        // this validation, set the 'required' attribute to 'false':
        // $builder->add('title', null, ['required' => false, ...]);

        $builder
            ->add('title', null, [
                'attr' => ['autofocus' => true],
                'label' => 'label.title',
            ])
            ->add('summary', TextareaType::class, [
                'help' => 'help.post_summary',
                'label' => 'label.summary',
            ])
            ->add('content', null, [
                'attr' => ['rows' => 20],
                'help' => 'help.post_content',
                'label' => 'label.content',
            ])
            ->add('publishedAt', DateTimePickerType::class, [
                'label' => 'label.published_at',
                'help' => 'help.post_publication',
            ])
            ->add('tags', TagsInputType::class, [
                'label' => 'label.tags',
                'required' => false,
            ])
            // form events let you modify information or fields at different steps
            // of the form handling process.
            // See https://symfony.com/doc/current/form/events.html
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
                /** @var Post $post */
                $post = $event->getData();
                if (null === $post->getSlug() && null !== $post->getTitle()) {
                    $post->setSlug($this->slugger->slug($post->getTitle())->lower());
                }
            })
        ;
		error_log(sprintf('[WARN] <<< Exiting buildForm($builder=%s,$options=%s)', is_scalar($builder) ? $builder : gettype($builder), is_scalar($options) ? $options : gettype($options)));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
		error_log(sprintf('[ERROR] >>> Entering configureOptions($resolver=%s)', is_scalar($resolver) ? $resolver : gettype($resolver)));
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
		error_log(sprintf('[WARN] <<< Exiting configureOptions($resolver=%s)', is_scalar($resolver) ? $resolver : gettype($resolver)));
    }
}
