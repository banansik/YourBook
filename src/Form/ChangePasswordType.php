<?php

/**
 * Change Password type.
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChangePasswordType.
 */
class ChangePasswordType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder The form builder
     * @param array                                        $options The options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /* $builder->add('roles', ChoiceType::class, [
             'label' => 'users_permissions',
             'choices' => ['common_admin' => 'ROLE_ADMIN', 'common_user' => 'ROLE_USER'],
             'expanded' => true,
             'multiple' => false,
         ]);
         $builder->get('roles')
             ->addModelTransformer(new CallbackTransformer(
                 function ($rolesArray) {
                     // transform the array to a string
                     return count($rolesArray) ? $rolesArray[0] : null;
                 },
                 function ($rolesString) {
                     // transform the string back to an array
                     return [$rolesString];
                 }
             ));
         */
        $builder->add(
            'email',
            TextType::class,
            [
                'label' => 'user_email',
                'required' => true,
                'attr' => ['max_length' => 64],
            ]
        );

        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => ['label' => 'password', 'required' => true],
            'second_options' => ['label' => 'repeat_password', 'required' => true],
        ]);
    }

    /**
     * Configures the options for this type.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'user';
    }
}