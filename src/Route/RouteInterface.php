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
    public function match(Request $request): bool;

    public function getHandler(): callable;
}
