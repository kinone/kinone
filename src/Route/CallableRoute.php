<?php
/**
 * Description of CallableRoute.php.
 *
 * @package Kinone\Kinone\Route
 */

namespace Kinone\Kinone\Route;

use Kinone\Kinone\Request;

class CallableRoute extends AbstractRoute
{
    public function match(Request $request): bool
    {
        if (!in_array($request->getMethod(), $this->methods)) {
            return false;
        }

        $pathinfo = strtolower(rtrim($request->getPathInfo(), '/'));

        return $pathinfo == $this->path;
    }
}
