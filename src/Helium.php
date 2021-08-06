<?php

namespace Helium;

use Exception;
use Helium\Core\Router;
use ReflectionClass;


class Helium
{
    const ERROR_GENERAL = 'Error! Unable to Render Page.';
    const ERROR_NO_URL = 'Error! Cannot find the URL.';

    protected $request;
    private static $instance;
    private $routes;
    private $configs;

    public function __construct()
    {
        $this->setConfigs();
        $this->setRoutes();
    }

    public static function getInstance(): Helium
    {
        if ( self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Start the process...
     * @param string $request
     */
    public static function run(string $request)
    {
        $helium = self::getInstance();

        $helium->request = $request;
        $controllerOutput = self::ERROR_GENERAL;

        try {
            //GET Controller / Action / Params from request
            $router = new Router($helium->routes, $request);
            $controllerName = $router->getController();
            $actionName = $router->getAction();
            $params = $router->getParams();

            // Get the path of the controller
            $controllerPath = APP_ROOT . DS . 'src' . DS . 'controllers' . DS . $controllerName.'.php';
            $controllerOutput = self::ERROR_NO_URL;

            //Does the controller file exist?
            if( file_exists( $controllerPath ) ) {
                require $controllerPath;
                // Does the controller class exist?
                $controllerClassName = 'Helium\Controller\\' . $controllerName.'Controller';
                if ( class_exists( $controllerClassName ) ) {
                    // Create the new controller class with the action and parameters
                    $controllerObject = new $controllerClassName( $controllerName, $actionName, $params );
                    if ( method_exists($controllerObject, $actionName) ) {
                        // Get the output of the controller of the given action with the params provided
                        $controllerOutput = $controllerObject->$actionName( $params );
                    } else {
                        $helium->printError( 'Cannot find '.$controllerName.' method: '. $actionName );
                    }
                } else {
                    $helium->printError( 'Cannot find controller class: ' .$controllerClassName );
                }
            } else {
                $helium->printError( 'Cannot find controller file: ' . $controllerPath );
            }
        } catch ( Exception $e ) {
            if ( self::getConfig('dev_mode') ) {
                $helium->printException( $e );
            }
        } finally {
            echo $controllerOutput;
        }
    }

    /**
     * Get Global Config setting
     * @param string $key
     * @return string|null
     */
    public static function getConfig(string $key): ?string
    {
        $helium = self::getInstance();

        return $helium->configs[$key] ?? null;
    }

    /**
     * Prints exceptions for debugging
     * @param $e
     */
    private function printException( $e )
    {
        echo '<pre>';
        echo $e->getMessage() . "\n\n";
        echo $e->getTraceAsString();
        echo '</pre>';
    }

    /**
     * Prints error for debugging
     * @param string $error
     */
    private function printError(string $error)
    {
        echo '<pre>', $error, '</pre>';
        die();
    }

    public static function setRegister($key, $value)
    {
        session_start();
        $_SESSION[$key] = $value;
    }

    public static function seeRegister($key)
    {
        session_start();
        return $_SESSION[$key];
    }

    public static function getRegister($key)
    {
        session_start();
        $value = $_SESSION[$key];
        unset($_SESSION[$key]);

        return $value;
    }

    /**
     * Gets all routes for the app
     */
    private function setConfigs(): void
    {
        $configsClassRoot = new \HeliumConfig\Configs;

        $this->configs = $configsClassRoot::all;
    }

    /**
     * Gets all routes for the app
     */
    private function setRoutes(): void
    {
        $routesClassRoot = new \HeliumConfig\Routes;
        $routesClass = new ReflectionClass($routesClassRoot);
        $routes = [];
        foreach ($routesClass->getConstants() as $controller => $actions) {
            foreach ($actions as $action => $isVisible) {
                $key = $controller . DS . $action;
                $routes[$key] = [
                    'controller' => $controller,
                    'action' => $action,
                    'visible' => $isVisible
                ];
            }
        }

        $this->routes = $routes;
    }

    public function getVisibleRoutes(): array
    {
        $visible = [];
        foreach ($this->routes as $route => $inner) {
            if ($inner['visible']) {
                $visible[] = $route;
            }
        }

        return $visible;
    }
}