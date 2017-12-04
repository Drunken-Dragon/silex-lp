<?php
$login = $app['controllers_factory'];
$login->get('/login', function () use ($app) {
    return $app['twig']->render('login.html.twig');
})
    ->bind('login');
