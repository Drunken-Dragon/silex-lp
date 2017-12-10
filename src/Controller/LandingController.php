<?php

namespace Controller;

class LandingController
{
    private $app;
    private $session;
    private $twig;

    public function __construct($app, $session, $twig)
    {
        $this->app = $app;
        $this->session = $session;
        $this->twig = $twig;
    }

    public function verifyAccess()
    {
        if (($this->session->get('is_logged')) === 1) {
            return $this->twig->render('landing.html.twig');
        } else {
            return $this->app->redirect('/login');
        }
    }
}