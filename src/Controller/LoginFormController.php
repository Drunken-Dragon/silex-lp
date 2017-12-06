<?php
namespace Controller;

use Silex\Provider\FormServiceProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class LoginFormController
{
    private $form;
    private $app;

    public function __construct($formFactory)
    {
        $this->form = $form;
        $this->app = $app;

        $this->form = $app['form.factory']->createBuilder(FormType::class, $data)
            ->add(
                'name',
                TextType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank()
                    ],
                    'label' => false,
                    'attr' => ['placeholder' => 'User'],
                ]
            )
            ->add(
                'password',
                \Symfony\Component\Form\Extension\Core\Type\PasswordType::class,
                [
                    'label' => false,
                    'attr' => ['placeholder' => 'Password'],
                ]
            )
            ->getForm();
    }
}