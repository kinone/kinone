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
    public function apply(Application $app, callable $next);
}
