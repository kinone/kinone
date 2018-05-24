<?php
/**
 * Description of Controller.php.
 *
 * @package Kinone\Kinone
 */

namespace Kinone\Kinone;

class Controller
{
    /**
     * @var $app;
     */
    protected $app;

    final public function __construct(Application $app)
    {
        $this->app = $app;
    }
}
