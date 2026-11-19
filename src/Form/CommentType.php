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

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines the form used to create and manipulate blog comments. Although in this
 * case the form is trivial and we could build it inside the controller, a good
 * practice is to always define your forms as classes.
 *
 * See https://symfony.com/doc/current/forms.html#creating-form-classes
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
final class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		error_log(sprintf('[ERROR] >>> Entering buildForm($builder=%s,$options=%s)', is_scalar($builder) ? $builder : gettype($builder), is_scalar($options) ? $options : gettype($options)));
        // By default, form fields include the 'required' attribute, which enables
        // the client-side form validation. This means that you can't test the
        // server-side validation errors from the browser. To temporarily disable
        // this validation, set the 'required' attribute to 'false':
        // $builder->add('content', null, ['required' => false]);

        $builder
            ->add('content', TextareaType::class, [
                'help' => 'help.comment_content',
            ])
        ;
		error_log(sprintf('[WARN] <<< Exiting buildForm($builder=%s,$options=%s)', is_scalar($builder) ? $builder : gettype($builder), is_scalar($options) ? $options : gettype($options)));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
		error_log(sprintf('[ERROR] >>> Entering configureOptions($resolver=%s)', is_scalar($resolver) ? $resolver : gettype($resolver)));
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
		error_log(sprintf('[WARN] <<< Exiting configureOptions($resolver=%s)', is_scalar($resolver) ? $resolver : gettype($resolver)));
    }
}
