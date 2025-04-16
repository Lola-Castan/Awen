<?php

namespace App\Form;

use App\Entity\user;
use App\Entity\Product;
use App\Enum\ProductStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('shortDescription')
            ->add('longDescription')
            ->add('stock')
            ->add('weight')
            ->add('width')
            ->add('depth')
            ->add('height')
            ->add('price')
            ->add('showcaseProduct')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Brouillon' => ProductStatus::Draft,
                    'Publié' => ProductStatus::Published,
                    'Archivé' => ProductStatus::Archived,
                ],
                'choice_label' => fn(ProductStatus $status) => match($status) {
                    ProductStatus::Draft => 'Brouillon',
                    ProductStatus::Published => 'Publié',
                    ProductStatus::Archived => 'Archivé',
                },
            ])
            // todo : logged in user should be asigned as creator (when not admin)
            ->add('creator', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'placeholder' => 'Sélectionner un utilisateur',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

