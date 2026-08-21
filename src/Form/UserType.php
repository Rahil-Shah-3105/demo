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

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines the form used to edit an user.
 *
 * @author Romain Monteil <monteil.romain@gmail.com>
 */
final class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		error_log(sprintf('>>> Entering buildForm($builder=%s,$options=%s)', is_scalar($builder) ? $builder : gettype($builder), is_scalar($options) ? $options : gettype($options)));
        // For the full reference of options defined by each form field type
        // see https://symfony.com/doc/current/reference/forms/types.html

        // By default, form fields include the 'required' attribute, which enables
        // the client-side form validation. This means that you can't test the
        // server-side validation errors from the browser. To temporarily disable
        // this validation, set the 'required' attribute to 'false':
        // $builder->add('title', null, ['required' => false, ...]);

        $builder
            ->add('username', TextType::class, [
                'label' => 'label.username',
                'disabled' => true,
            ])
            ->add('fullName', TextType::class, [
                'label' => 'label.fullname',
            ])
            ->add('email', EmailType::class, [
                'label' => 'label.email',
            ])
        ;
		error_log(sprintf('<<< Exiting buildForm($builder=%s,$options=%s)', is_scalar($builder) ? $builder : gettype($builder), is_scalar($options) ? $options : gettype($options)));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
		error_log(sprintf('>>> Entering configureOptions($resolver=%s)', is_scalar($resolver) ? $resolver : gettype($resolver)));
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
		error_log(sprintf('<<< Exiting configureOptions($resolver=%s)', is_scalar($resolver) ? $resolver : gettype($resolver)));
    }
}
