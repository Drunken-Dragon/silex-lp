<?php

namespace Controller;

class AuthController
{
    private $twig;
    public function __construct($twig)
    {
        $this->twig = $twig;
    }
    public function loginAction()
    {
        return $this->twig->render('login.html.twig');
    }
}