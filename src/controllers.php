<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

$app->match('/login', function (Request $request) use ($app) {

    $form = $app['form.factory']->createBuilder(FormType::class, $data)
        ->add('name', TextType::class, [
            'constraints' => [new Assert\NotBlank()]
        ])
        ->add('password', \Symfony\Component\Form\Extension\Core\Type\PasswordType::class, [])
        ->getForm();

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

    return $app['twig']->render('login.html.twig', array('form' => $form->createView()));
});

$app->get('/', function () use ($app) {
    if (($app['session']->get('is_logged')) === 1) {
        return $app['twig']->render('landing.html.twig');
    } else {
        return $app->redirect('/login');
    }
})
->bind('index');

//$app->get('/login', function () use ($app) {
//    return $app['twig']->render('login.html.twig');
//})
//->bind('login');

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return new Response($e->getMessage());
    }

    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );
    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
