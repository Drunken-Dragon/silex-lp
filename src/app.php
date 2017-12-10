<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\CsrfServiceProvider;
use Symfony\Component\Dotenv\Dotenv;

$app = new Application();
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new CsrfServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => array(
        __DIR__.'/views',
        __DIR__.'/../vendor/braincrafted/bootstrap-bundle/Braincrafted/Bundle/BootstrapBundle/Resources/views/Form'
    )
));
$app->register(new Silex\Provider\TranslationServiceProvider(), [
    'locale_fallbacks' => ['en'],
]);
$app->register(new Silex\Provider\TranslationServiceProvider(), [
    'translator.domains' => [],
]);
$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');
$app->register(new \Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => 'pdo_mysql',
        'dbname' => getenv('DB_NAME'),
        'host' => getenv('DB_HOST'),
        'user' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
        'charset' => 'UTF8',
    ]
]);
$app['twig'] = $app->extend('twig', function ($twig, $app) {
    $twig->addExtension(new \Braincrafted\Bundle\BootstrapBundle\Twig\BootstrapLabelExtension);
    $twig->addExtension(new \Braincrafted\Bundle\BootstrapBundle\Twig\BootstrapBadgeExtension);
    $twig->addExtension(new \Braincrafted\Bundle\BootstrapBundle\Twig\BootstrapFormExtension);

    return $twig;
});

$app['auth.controller'] = function () use ($app) {
    return new \Controller\AuthController($app, $app['db'], $app['session']);
};

$app['landing.controller'] = function () use ($app) {
    return new \Controller\LandingController($app, $app['session'], $app['twig']);
};

return $app;
