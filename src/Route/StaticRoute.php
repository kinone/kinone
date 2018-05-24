<?php
/**
 * Description of StaticRoute.php.
 *
 * @package Kinone\Kinone\Route
 */

namespace Kinone\Kinone\Route;

use Kinone\Kinone\Request;
use Kinone\Kinone\RouteCollection;
use ReflectionClass;

class StaticRoute extends AbstractRoute
{
    const DEFAULT_ACTION = 'index';

    public function match(Request $request): bool
    {
        $pathinfo = strtolower(rtrim($request->getPathInfo(), '/'));

        return $pathinfo == $this->path ||
            $pathinfo . '/' . self::DEFAULT_ACTION == $this->path;
    }

    public function getHandler(): callable
    {
        list($ins, $method) = call_user_func($this->handler);

        return new Handler($ins, $method);
    }

    /**
     * @param string $prefix
     * @param ReflectionClass $ins
     * @return RouteCollection
     */
    public static function resolve(string $prefix, ReflectionClass $ins)
    {
        $methods = $ins->getMethods(\ReflectionMethod::IS_PUBLIC);

        $rc = new RouteCollection();
        foreach ($methods as $method) {
            $name = $method->getName();
            if (strncasecmp('action', substr($name, -6), 6) != 0) {
                continue;
            }

            $callable = function () use ($ins, $method) {
                return [$ins, $method];
            };

            $path = rtrim($prefix, '/') . '/' . substr($name, 0, -6);
            $rc->add(new self($path, $callable));
        }

        return $rc;
    }
}
