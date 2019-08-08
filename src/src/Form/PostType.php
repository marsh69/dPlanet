<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Trend;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('body', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Your text here'
                ],
                'label' => false
            ])
            ->add('image', ImageType::class, [
                'required' => false
            ])
            ->add('trends', EntityType::class, [
                'label' => 'Trends (max. 5)',
                'multiple' => true,
                'class' => Trend::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'select2'
                ],
                'required' => false
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class
        ]);
    }
}
