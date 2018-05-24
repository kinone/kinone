<?php
/**
 * Description of PluginInterface.php.
 *
 * @package Kinone\Kinone\Plugin
 */

namespace Kinone\Kinone\Plugin;

use Kinone\Kinone\Application;

interface PluginInterface
{
    /**
     * The callable {$next} should be called and return the result.
     *
     * @param Application $app
     * @param callable $next
     * @return mixed
     */
    public function apply(Application $app, callable $next);
}
