<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            /*->add('roles', ChoiceType::class, [
                'expanded' => true,
                'multiple' => true,
                'mapped' => false,
                'label_attr' => ['class' => 'form-check form-check-inline'],
                'choices' => ['Admin' => 'ROLE_ADMIN', 'User' => 'ROLE_USER']
            ])*/
            ->add('plainPassword', PasswordType::class, [
                'required' => false,
                'mapped'   => false,
                'help'     => 'Leave blank if you don\'t want to change your password'
            ])
            ->add('username', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
