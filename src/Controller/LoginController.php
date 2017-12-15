<?php
declare(strict_types=1);

namespace Controller;

use Form\LoginRequest;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends AbstractController
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $data = new LoginRequest();
        $form = $this->getFormFactory()->create(\Form\LoginType::class, $data);
        $form->handleRequest($request);
        $db = $this->app['db'];

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $db->fetchAll('SELECT * FROM users WHERE name = ?', [$data->name]);

            if (password_verify($data->password, $user[0]['password'])) {
                if ($this->getSession()->get('is_logged') !== 1) {
                    $this->getSession()->set('is_logged', 1);
                    $this->getSession()->set('user', ['name' => $data->name]);

                    return $this->render('landing.html.twig');
                }
            } else {
                return $this->render('login.html.twig', ['form' => $form->createView()]);
            }
        }

        return $this->render('login.html.twig', ['form' => $form->createView()]);
    }
}