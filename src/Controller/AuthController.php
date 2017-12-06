<?php

namespace Controller;

class AuthController
{
    private $twig;
    public function __construct($twig, $form)
    {
        $this->twig = $twig;
        $this->form = $form;
    }
    public function loginAction()
    {
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $user = $app['db']->fetchAll('SELECT * FROM users WHERE name = ?', [$data['name']]);

            if (password_verify($data['password'], $user[0]['password'])) {
                if ($app['session']->get('is_logged') != 1) {
                    $app['session']->set('is_logged', 1);
                    $app['session']->set('user', ['name' => $data['name']]);

                    return $app->redirect('/');
                }
            } else {
                return $app->redirect('/login');
            }
        }
        return $this->twig->render('login.html.twig', array('form' => $form->createView()));
    }
}
