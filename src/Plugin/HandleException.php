<?php
/**
 * Description of Debug.php.
 *
 * @package Kinone\Kinone\Plugin
 */

namespace Kinone\Kinone\Plugin;

use Kinone\Kinone\Application;

class HandleException implements PluginInterface
{
    /**
     * The callable {$next} should be called and return the result.
     *
     * @param Application $app
     * @param callable $next
     * @return mixed
     */
    public function apply(Application $app, callable $next)
    {
        try {
            return $next();
        } catch (\Exception $e) {
            return $app->handleException($e);
        }
    }
}
