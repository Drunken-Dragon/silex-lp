<?php
declare(strict_types=1);

namespace Controller;

use Silex\Application;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig_Environment;

class AbstractController
{
    public $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return Twig_Environment
     */
    public function getTwig(): Twig_Environment
    {
        return $this->app['twig'];
    }

    /**
     * @param $template
     * @param $data
     * @return string
     */
    public function render($template, array $data = []) : string
    {
        try {
            return $this->getTwig()->render($template, $data);
        } catch (\Twig_Error_Loader $e) {
            return '';
        } catch (\Twig_Error_Runtime $e) {
            return '';
        } catch (\Twig_Error_Syntax $e) {
            return '';
        }
    }

    /**
     * @return FormFactory
     */
    public function getFormFactory() : FormFactory
    {
        return $this->app['form.factory'];
    }

    /**
     * @return Session;
     */
    public function getSession() : Session
    {
        return $this->app['session'];
    }
}