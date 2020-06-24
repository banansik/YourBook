<?php
/**
 * Author type.
 */

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AuthorType
 */

class AuthorType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder The form builder
     * @param array                                        $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'title',
            TextType::class,
            [
                'label' => 'label_author',
                'required' => true,
                'attr' => ['max_length' => 100],
            ]
        );
        $builder->add(
            'description',
            TextType::class,
            [
                'label' => 'label_description',
                'required' => true,
                'attr' => [
                    'max_length' => 2000,
                ],
            ]
        );
    }

    /**
     * Configure the options for this type.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Author::class]);
    }

    /**
     * Returns the prefix of the template block for this type.
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'author';
    }
}
