<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\CsrfServiceProvider;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
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
$app->register(new \Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => 'pdo_mysql',
        'dbname' => 'landing_form_2',
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'root',
        'charset' => 'UTF8',
    ]
]);
$app['twig'] = $app->extend('twig', function ($twig, $app) {
//    $twig->addExtension(new \Braincrafted\Bundle\BootstrapBundle\Twig\BootstrapIconExtension);
    $twig->addExtension(new \Braincrafted\Bundle\BootstrapBundle\Twig\BootstrapLabelExtension);
    $twig->addExtension(new \Braincrafted\Bundle\BootstrapBundle\Twig\BootstrapBadgeExtension);
    $twig->addExtension(new \Braincrafted\Bundle\BootstrapBundle\Twig\BootstrapFormExtension);

    return $twig;
});

$app->mount('/login', include "login_controller.php");

return $app;
