<?php

namespace App\Form;

use App\Entity\Developer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeveloperType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First name'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last name'
            ])
            ->add('username', TextType::class, [
                'label' => 'Username'
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail address'
            ])
            ->add('profileImage', ImageType::class, [
                'label' => 'Profile picture',
                'required' => false
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Your password'
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Developer::class
        ]);
    }
}
