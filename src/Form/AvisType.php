<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', ChoiceType::class, [
                'label'   => 'Note',
                'choices' => [
                    '⭐ 1 étoile'   => 1,
                    '⭐⭐ 2 étoiles' => 2,
                    '⭐⭐⭐ 3 étoiles' => 3,
                    '⭐⭐⭐⭐ 4 étoiles' => 4,
                    '⭐⭐⭐⭐⭐ 5 étoiles' => 5,
                ],
                'attr' => ['class' => 'form-select'],
                'constraints' => [
                    new Assert\NotBlank(message: 'Choisissez une note'),
                    new Assert\Range(['min' => 1, 'max' => 5]),
                ],
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire',
                'attr'  => ['class' => 'form-control', 'rows' => 4],
                'constraints' => [
                    new Assert\NotBlank(message: 'Le commentaire est obligatoire'),
                    new Assert\Length([
                        'min'        => 10,
                        'max'        => 1000,
                        'minMessage' => 'Minimum 10 caractères',
                        'maxMessage' => 'Maximum 1000 caractères',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Avis::class]);
    }
}
