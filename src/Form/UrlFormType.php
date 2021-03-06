<?php

namespace App\Form;

use App\Entity\Url;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UrlFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', UrlType::class)
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
            ])
            ->add('is_active', CheckboxType::class, [
                'label_attr' => ['class' => 'switch-custom'],
                'required'   => false,
            ])
            ->add('is_public', CheckboxType::class, [
                'label_attr' => ['class' => 'switch-custom'],
                'required'   => false,
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $product = $event->getData();
            $form = $event->getForm();

            if ($product) {
                $form->add('urlHash');
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Url::class
        ]);
    }
}
