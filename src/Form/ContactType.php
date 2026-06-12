<?php

/**
 * This file is part of Portfolio project.
 * (c) Caroline Noyer <studio@carolinenoyer.fr>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Tito10047\AltchaBundle\Type\AltchaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'       => false,
                'attr'        => ['placeholder' => 'John Doe'],
                'constraints' => [
                    new Assert\NotBlank(message: 'Merci de renseigner votre nom.'),
                    new Assert\Length(min: 2),
                ],
            ])
            ->add('email', EmailType::class, [
                'label'       => false,
                'attr'        => ['placeholder' => 'john.doe@example.com'],
                'constraints' => [
                    new Assert\NotBlank(message: 'Merci de renseigner votre email.'),
                    new Assert\Email(message: 'L\'adresse email n\'est pas valide.'),
                ],
            ])
            ->add('subject', TextType::class, [
                'label'       => false,
                'attr'        => ['placeholder' => 'Demande de renseignements'],
                'constraints' => [new Assert\NotBlank(message: 'Merci de renseigner un sujet.')],
            ])
            ->add('message', TextareaType::class, [
                'label'       => false,
                'attr'        => ['placeholder' => 'Décrivez votre demande...', 'rows' => 6],
                'constraints' => [
                    new Assert\NotBlank(message: 'Merci de renseigner votre message.'),
                    new Assert\Length(min: 10, minMessage: 'Votre message est trop court.'),
                ],
            ])
            ->add('security', AltchaType::class, [
                'label'       => false,
                'floating'    => true,
                'hide_logo'   => false,
                'hide_footer' => false,
                // Optional: override global config
                // 'cost' => 5000,
                // 'timeout' => 30.0,
                // 'counter_min' => 5000,
                // 'counter_max' => 10000,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
