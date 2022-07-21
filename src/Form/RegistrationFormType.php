<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('username', TextType::class, [
        "label" => "Nom d'utilisateur",
        "attr" => [
          "placeholder" => "Nom d'utilisateur"
        ],
        "constraints" => [
          new Length([
            "min" => 2,
            "minMessage" => "Votre nom d'utilisateur doit contenir au moins {{ limit }} caractères"
          ])
        ]
      ])
      ->add('email', EmailType::class, [
        "label" => "Adresse email",
        "attr" => [
          "placeholder" => "Adresse email"
        ]
      ])
      ->add('plainPassword', PasswordType::class, [
        "label" => "Mot de passe",
        // instead of being set onto the object directly,
        // this is read and encoded in the controller
        'mapped' => false,
        'attr' => [
          'autocomplete' => 'new-password',
          "placeholder" => "Mot de passe"
        ],
        'constraints' => [
          new NotBlank([
            'message' => 'Veuillez saisir un mot de passe valide',
          ]),
          new Length([
            'min' => 6,
            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
            // max length allowed by Symfony for security reasons
            'max' => 4096,
          ]),
        ],
      ])
      ->add('save', SubmitType::class, ["label" => "M'inscrire"]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}