<?php

namespace Controller;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;


class AuthController
{
    private $app;
    private $form;
    private $twig;
    private $db;

    public function __construct($app, $db)
    {
        $this->db = $app['db'];
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

    public function loginAction(Request $request)
    {

        $this->form->handleRequest($request);
        $db = $this->db;

        if ($this->form->isValid()) {
            $data = $this->form->getData();

            $user = $db->fetchAll('SELECT * FROM users WHERE name = ?', [$data['name']]);

            if (password_verify($data['password'], $user[0]['password'])) {
                dump($app['session']);
                die();
                if ($app['session']->get('is_logged') != 1) {
                    $app['session']->set('is_logged', 1);
                    $app['session']->set('user', ['name' => $data['name']]);

                    return $app->redirect('/');
                }
            } else {
                return $app->redirect('/login');
            }
        }
//        return $this->twig->render('login.html.twig', array('form' => $form->createView()));
        return $app->redirect('/login');
    }
}
