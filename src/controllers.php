<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Silex\Provider\FormServiceProvider;

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
            $app['session']->start();

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
