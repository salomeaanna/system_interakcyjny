<?php
/**
 * User type.
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class UserType.
 */
class UserType extends AbstractType
{
    /**
     * Builds form.
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array                $options Options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'data' => $options['current_email'],
                    'label' => 'label.user_email',
                    'constraints' => [
                        new NotBlank(),
                        new Email(),
                        new Length(['min' => 3, 'max' => 200]),
                    ],
                ]
            )
            ->add(
                'current_password',
                PasswordType::class,
                [
                    'label' => 'label.user_current_password',
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'message.password_fields_must_match',
                    'constraints' => [
                        new NotBlank(),
                    ],
                    'first_options' => [
                        'label' => 'label.user_new_password',
                    ],
                    'second_options' => [
                        'label' => 'label.user_new_password_repeat',
                    ],
                ]
            )
        ;
    }

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver Options resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('current_email', '');

        $resolver->setAllowedTypes('current_email', 'string');
    }
}
