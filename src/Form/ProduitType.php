<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints as Assert;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => ['class' => 'form-control'],
                'constraints' => [new Assert\NotBlank(), new Assert\Length(['min'=>2,'max'=>255])],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 4],
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix (TND)',
                'attr' => ['class' => 'form-control', 'step' => '0.01'],
                'constraints' => [new Assert\NotBlank(),
                    new Assert\Positive(message: 'Le prix doit être positif'),
                    new Assert\LessThan(value: 99999, message: 'Prix trop élevé'),],
            ])
            ->add('stock', IntegerType::class, [
                'label' => 'Stock',
                'attr' => ['class' => 'form-control'],
                'constraints' => [new Assert\NotBlank(), new Assert\PositiveOrZero()],
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'label' => 'Categorie',
                'attr' => ['class' => 'form-select'],
                'constraints' => [new Assert\NotNull()],
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image du produit',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
