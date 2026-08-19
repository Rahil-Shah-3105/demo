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
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Defines the form used to change user's password.
 *
 * @author Romain Monteil <monteil.romain@gmail.com>
 */
final class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		error_log(sprintf('>>> Entering buildForm($builder=%s,$options=%s)', is_scalar($builder) ? $builder : gettype($builder), is_scalar($options) ? $options : gettype($options)));
        $builder
            ->add('currentPassword', PasswordType::class, [
                'constraints' => [
                    new UserPassword(),
                ],
                'label' => 'label.current_password',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'off',
                ],
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank(),
                    new Length(
                        min: 5,
                        max: 128,
                    ),
                ],
                'first_options' => [
                    'hash_property_path' => 'password',
                    'label' => 'label.new_password',
                ],
                'mapped' => false,
                'second_options' => [
                    'label' => 'label.new_password_confirm',
                ],
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
