<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

$app->match('/form', function (Request $request) use ($app) {
    // some default data for when the form is displayed the first time
    $data = array(
        'name' => 'Your name',
        'email' => 'Your email',
    );

    $form = $app['form.factory']->createBuilder(FormType::class, $data)
        ->add('name')
        ->add('email')
        ->add('billing_plan', ChoiceType::class, array(
            'choices' => array('free' => 1, 'small business' => 2, 'corporate' => 3),
            'expanded' => true,
        ))
        ->add('submit', SubmitType::class, [
            'label' => 'Save',
        ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        // do something with the data

        // redirect somewhere
        return $app->redirect('...');
    }

    // display the form
    return $app['twig']->render('form.twig', array('form' => $form->createView()));
});

$app->get('/', function () use ($app) {
    if (($app['session']->get('is_logged')) === 1) {
        return $app['twig']->render('index.html.twig');
    } else {
        return $app->redirect('/login');
    }
})
->bind('index');

$app->get('/login', function () use ($app) {
    return $app['twig']->render('login.html.twig');
})
->bind('login');

$app->post('/login', function (Request $request) use ($app) {
    $name = $request->get('name');
    $password = $request->get('password');
    $user = $app['db']->fetchAssoc('SELECT * FROM users WHERE name = ?', [$name]);

    if (password_verify($password, $user['password'])) {
        if ($app['session']->get('is_logged') != 1) {
            $app['session']->set('is_logged', 1);
            $app['session']->set('user', ['name' => $name]);

            return $app->redirect('/');
        }
    } else {
        return $app->redirect('/login');
    }
});

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return new Response($e->getMessage());
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );
    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
