<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Silex\Provider\FormServiceProvider;

$app->get('/', function () use ($app) {

        if (($app['session']->get('is_logged')) === 1) {
            var_dump($app['session']->isStarted());
            return $app['twig']->render('index.html.twig');
        } else {
            return $app['twig']->render('login.html.twig');
        }
})
->bind('index');

$app->get('/login', function () use ($app) {
    return $app['twig']->render('login.html.twig');


})
->bind('login');

    $app->post('/login', function (Request $request) use ($app) {

    //    $login = false;
    $name = $request->get('name');
    $password = $request->get('password');
    $user = $app['db']->fetchAssoc('SELECT * FROM users WHERE name = ?', [$name]);

    if (password_verify($password, $user['password'])) {
//        die(var_dump($user['password']));
        if (($app['session']->get('is_logged')) != 1) {
            $app['session']->set('is_logged', 1);
            $app['session']->set('user', ['name' => $name]);
            $app['session']->start();
            return $app->redirect('/');
        }
    } else {
        echo 'Please check your input';
    }
});





//    $user = $app['password']

    if ($login) {
        $app['session']->set('is_logged', 1);
        $app['session']->set('user', ['name' => $name]);

        return $app->redirect('/');
    }

    return $app['twig']->render('login.html.twig', [
        'name' => $name
    ]);

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    return new Response($e->getMessage());


    if ($app['debug']) {
        return;
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
