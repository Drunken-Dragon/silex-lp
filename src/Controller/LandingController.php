<?php

namespace Controller;

class LandingController extends AbstractController
{

    public function verifyAccess()
    {
        if (($this->app['session']->get('is_logged')) === 1) {
            $leadForm = $this->getFormFactory()->create(\Form\LeadType::class);
            return $this->render('landing.html.twig', ['form' => $leadForm->createView()]);
        }
        return $this->app->redirect('/login');



//        if (($this->session->get('is_logged')) === 1) {
//            return $this->render('landing.html.twig');
//        } else {
//            return $this->app->redirect('/login');
//        }
    }
}
