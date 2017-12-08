<?php

namespace Controller;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;


class AuthController
{
    private $app;
    private $form;
    private $twig;

    public function __construct($app)
    {
        $this->twig = $app['twig'];
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

    public function displayForm()
    {
        return $this->twig->render('login.html.twig', [
            'form' => $this->form->createView()
        ]);
    }

//    public function loginAction()
//    {
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {
//            $data = $form->getData();
//            $user = $app['db']->fetchAll('SELECT * FROM users WHERE name = ?', [$data['name']]);
//
//            if (password_verify($data['password'], $user[0]['password'])) {
//                if ($app['session']->get('is_logged') != 1) {
//                    $app['session']->set('is_logged', 1);
//                    $app['session']->set('user', ['name' => $data['name']]);
//
//                    return $app->redirect('/');
//                }
//            } else {
//                return $app->redirect('/login');
//            }
//        }
//        return $this->twig->render('login.html.twig', array('form' => $form->createView()));
//    }
}
