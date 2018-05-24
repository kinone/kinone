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
     * @var \ReflectionClass
     */
    private $refc;

    /**
     * @var \ReflectionMethod
     */
    private $refm;

    public function __construct(\ReflectionClass $refc, \ReflectionMethod $refm)
    {
        $this->refc = $refc;
        $this->refm = $refm;
    }

    /**
     * @param Application $app
     * @return Controller
     */
    public function getController(Application $app)
    {
        static $ins = null;
        if (null == $ins) {
            $ins = $this->refc->newInstance($app);
            if (!$ins instanceof Controller) {
                throw new Exception(sprintf("Types error"));
            }
        }

        return $ins;
    }

    public function __invoke(Application $app)
    {
        return $this->refm->invoke($this->getController($app));
    }
}
