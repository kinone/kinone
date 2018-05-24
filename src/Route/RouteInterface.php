<?php
/**
 * Description of RouteInterface.php.
 *
 * @package Kinone\Kinone\Route
 */

namespace Kinone\Kinone\Route;

use Kinone\Kinone\Request;

interface RouteInterface
{
    /**
     * @param Request $request
     * @return bool
     */
    public function match(Request $request): bool;

    /**
     * @return callable
     */
    public function getHandler(): callable;
}
