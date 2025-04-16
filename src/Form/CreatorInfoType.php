<?php

namespace App\Form;

use App\Entity\Embeddable\CreatorInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreatorInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('displayName', TextType::class, [
            'label' => 'Nom public',
            'required' => true,
            ])
            ->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => false,
            ])
            ->add('website', TextType::class, [
            'label' => 'Site web',
            'required' => false,
            ])
            ->add('instagramProfile', TextType::class, [
            'label' => 'Profil Instagram',
            'required' => false,
            ])
            ->add('facebookProfile', TextType::class, [
            'label' => 'Profil Facebook',
            'required' => false,
            ])
            ->add('pinterestProfile', TextType::class, [
            'label' => 'Profil Pinterest',
            'required' => false,
            ])
            ->add('practicalInfos', TextareaType::class, [
            'label' => 'Informations pratiques',
            'required' => false,
            ])
            ->add('coverImage', TextType::class, [
            'label' => 'Image de couverture',
            'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreatorInfo::class,
        ]);
    }
}
