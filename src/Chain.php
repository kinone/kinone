<?php
/**
 * Description of Chain.php.
 *
 * @package Kinone\Kinone
 */

namespace Kinone\Kinone;

use Kinone\Kinone\Plugin\PluginInterface;

class Chain
{
    /**
     * @var PluginInterface[]
     */
    private $plugins;

    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->plugins = [];
        $this->app = $app;
    }

    public function build(callable $callable)
    {
        return array_reduce(array_reverse($this->plugins), function(callable $next, PluginInterface $plugin) {
            return function() use ($plugin, $next) {
                return $plugin->apply($this->app, $next);
            };
        }, function() use ($callable) {
            return call_user_func($callable);
        });
    }

    public function register(PluginInterface $plugin)
    {
        $this->plugins[] = $plugin;

        return $this;
    }
}
