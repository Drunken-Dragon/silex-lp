<?php

namespace Controller;

class LandingController extends AbstractController
{

    public function verifyAccess()
    {
        if (($this->app['session']->get('is_logged')) === 1) {
            return $this->render('landing.html.twig');
        }
        return $this->app->redirect('/login');



//        if (($this->session->get('is_logged')) === 1) {
//            return $this->render('landing.html.twig');
//        } else {
//            return $this->app->redirect('/login');
//        }
    }
}
