<?php
/**
 * Description of Application.php.
 *
 * @package Kinone
 */

namespace Kinone\Kinone;

use Kinone\Kinone\Plugin\HandleException;
use Kinone\Kinone\Plugin\PluginInterface;
use Kinone\Kinone\Route\Handler;
use Pimple\Container;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Application extends Container
{
    /**
     * @var string|array
     */
    private $config;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var string
     */
    private $env;

    /**
     * @var Chain
     */
    private $chain;

    /**
     * @var callable
     */
    private $exceptionHandler;

    public function __construct($config, $env = 'product')
    {
        parent::__construct();

        $this->config = $config;
        $this->env = $env;
        $this->dispatcher = new Dispatcher($this);
        $this->chain = new Chain($this);

        $this->chain->register(new HandleException());
    }

    public function mount(string $prefix, string $classname)
    {
        try {
            $obj = new \ReflectionClass($classname);
        } catch (\Exception $exception) {
            throw new Exception(sprintf("Class %s not found", $classname));
        }

        if (!$obj->isSubclassOf(Controller::class)) {
            throw new Exception(sprintf("Class %s should be subclass of %s", $classname, Controller::class));
        }

        $this->dispatcher->bind($prefix, $obj);

        return $this;
    }

    public function get(string $pattern, callable $handler)
    {
        $this->dispatcher->match($pattern, $handler)->method('GET');

        return $this;
    }

    public function post(string $pattern, callable $handler)
    {
        $this->dispatcher->match($pattern, $handler)->method('POST');

        return $this;
    }

    public function route(string $pattern, callable $handler)
    {
        $this->dispatcher->match($pattern, $handler)->method('GET|POST');
    }

    public function addPlugin(PluginInterface $plugin)
    {
        $this->chain->register($plugin);
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        static $request = null;
        if (null == $request) {
            $request = Request::createFromGlobals();
        }

        return $request;
    }

    /**
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    public function run()
    {
        $request = $this->getRequest();

        $handler = $this->dispatcher->dispatch($request);
        $chain = clone $this->chain;

        if ($handler instanceof Handler) {
            $controller = $handler->getController($this);
            if (is_callable($controller)) {
                $controller($chain);
            }
        }

        $getResponse = function () use ($handler) {
            $res = $handler($this);

            if ($res instanceof Response) {
                return $res;
            } else if (is_array($res) || $res instanceof \stdClass) {
                return new JsonResponse($res);
            } else if (is_string($res)) {
                return new Response($res);
            } else {
                return new Response('');
            }
        };

        $callable = $chain->build($getResponse);
        $response = $callable();
        if ($response instanceof Response) {
            $response->send();
        }
    }

    /**
     * @param callable $handler
     * @return Application
     */
    public function setExceptionHandler(callable $handler)
    {
        $this->exceptionHandler = $handler;

        return $this;
    }

    /**
     * @param \Exception $e
     * @return mixed
     */
    public function handleException(\Exception $e)
    {
        if (null == $this->exceptionHandler) {
            $this->exceptionHandler = function (\Exception $e) {
                return new JsonResponse([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]);
            };
        }

        return call_user_func($this->exceptionHandler, $e);
    }
}
