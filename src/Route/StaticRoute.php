<?php
/**
 * Description of StaticRoute.php.
 *
 * @package Kinone\Kinone\Route
 */

namespace Kinone\Kinone\Route;

use Kinone\Kinone\Application;
use Kinone\Kinone\Request;
use Kinone\Kinone\RouteCollection;
use ReflectionClass;

final class StaticRoute extends AbstractRoute
{
    const DEFAULT_ACTION = 'index';

    /**
     * @param Request $request
     * @return bool
     */
    public function match(Request $request): bool
    {
        $pathinfo = strtolower(rtrim($request->getPathInfo(), '/'));

        return $pathinfo == $this->path ||
            $pathinfo . '/' . self::DEFAULT_ACTION == $this->path;
    }

    /**
     * @return callable
     */
    public function getHandler(): callable
    {
        return call_user_func($this->handler);
    }

    /**
     * @param string $prefix
     * @param ReflectionClass $ins
     * @param Application $app
     * @return RouteCollection
     */
    public static function resolve(string $prefix, ReflectionClass $ins, Application $app)
    {
        $methods = $ins->getMethods(\ReflectionMethod::IS_PUBLIC);

        $rc = new RouteCollection();
        foreach ($methods as $method) {
            $name = $method->getName();
            if (strncasecmp('action', substr($name, -6), 6) != 0) {
                continue;
            }

            $callable = function () use ($method, $app) {
                return new Handler($method, $app);
            };

            $path = rtrim($prefix, '/') . '/' . substr($name, 0, -6);
            $rc->add(new self($path, $callable));
        }

        return $rc;
    }
}
