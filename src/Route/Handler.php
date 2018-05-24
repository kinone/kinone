<?php
/**
 * Description of Handler.php.
 *
 * @package Kinone\Kinone\Route
 */

namespace Kinone\Kinone\Route;

use Kinone\Kinone\Application;
use Kinone\Kinone\Controller;
use Kinone\Kinone\Exception;

class Handler
{
    /**
     * @var \ReflectionMethod
     */
    private $method;

    /**
     * @var Application
     */
    private $app;

    /**
     * @var Controller
     */
    private $controller;

    public function __construct(\ReflectionMethod $method, Application $app)
    {
        $this->method = $method;
        $this->app = $app;
        $this->controller = null;
    }

    /**
     * @return Controller
     */
    public function getController()
    {
        if (null == $this->controller) {
            $cls = $this->method->getDeclaringClass();
            $ins = $cls->newInstance($this->app);
            if (!$ins instanceof Controller) {
                throw new Exception(sprintf("Types error"));
            }

            $this->controller = $ins;
        }

        return $this->controller;
    }

    public function __invoke()
    {
        return $this->method->invoke($this->getController(), func_get_args());
    }
}
