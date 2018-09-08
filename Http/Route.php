<?php
/** 
 * @package Kekere Framework (23/08/2018)
 * @version 0.0.1
 * @license https://opensource.org/licenses/MIT
 * @author Tochukwu Nwachukwu <truetochukz@gmail.com> 
 * @link http://kekere.tochukwu.xyz 
 */

namespace App\Http;

/**
 * The Route class will handle routing functionality.
 */
class Route
{
    static $routes = [];

    /**
     * Maps url to controller-actions as defined in the routes file.
     * 
     * @return void
     */
    public static function mapRoute()
    {
           
        $uri = strtolower($_SERVER['REQUEST_URI']?? '');
        $method = strtoupper($_SERVER['REQUEST_METHOD']?? '');           
        
        $url = trim($uri, "/");          
        $urlSegments= explode("/", $url); 
        $routes = self::$routes; 
        $found = false;
        foreach($routes as $route){            
            if($route->url == $url && $route->method == $method){                            
                self::callAction($route);
            }elseif($route->method == $method && count($urlSegments) == count(explode("/", $route->url))){               
                $routeSegments = explode("/", $route->url);
                $args = self::matchRoute($routeSegments, $urlSegments);                
                if($args){
                    self::callAction($route, $args);
                }                                    
            }elseif($route->url == $url && $route->method != $method){                                
                 $found = 'partial';                                
            }           
        }
                
        if($found === 'partial'){
            exit("Method not allowed<br />");
        }elseif($found === false){
            $page = $url;
            return view('errors.404', ['page'=>$page]);
        }
    
    }

    /**
     * Calls the action method of the controller class.
     * 
     * @param stdClass $route
     * @param array $args
     * @param return void
     */
    public static function callAction(\stdClass $route, array $args = [])
    { 
        $routeControllerAction = explode("@", $route->controllerAction); 
        $controller = $routeControllerAction[0];
        $action = $routeControllerAction[1];
        $controllerObj = new $controller();
        $controllerObj->$action(...$args);     
        exit;             
    }

    /**
     * Matches a route having one or more parameters with a given url.
     * 
     * @param array $routeSegments
     * @param array $urlSegments
     * @return array|boolean
     */
    public static function matchRoute(array $routeSegments, array $urlSegments){         
        $args = [];
        for($i = 0; $i<count($routeSegments); $i++){           
            if(preg_match("/^\{{1}[a-zA-Z0-9_\-\s]+\}{1}$/", $routeSegments[$i])){
                $arg = str_replace(['\{', '\}'], "", $routeSegments[$i]);
                //$$arg = $urlSegments[$i];
                // $args[$arg] = $urlSegments[$i];
                $args[] = $urlSegments[$i];
                continue;
            }
            if($routeSegments[$i] != $urlSegments[$i]){
                return false;               
            }
        }
        return $args;
    }

    /**
     * Adds a route for a GET method to the array of routes.
     * 
     * @param string $url
     * @param string $controllerAction
     * @return void
     */
    public static function get(string $url, string $controllerAction)
    {
        self::addRoute($url, $controllerAction, 'GET');
    }

    /**
     * Adds a route for a POST method to the array of routes.
     * 
     * @param string $url
     * @param string $controllerAction
     * @return void
     */
    public static function post(string $url, string $controllerAction)
    {
       self::addRoute($url, $controllerAction, 'POST');
    }

    /**
     * Adds a route for a POST or GET method to the array of routes.
     * 
     * @param string url
     * @param string $controllerAction
     * @param string $method
     * @return void
     */
    protected static function addRoute(string $url, string $controllerAction, string $method)
    {      
       $newRoute = new \stdClass();
       $newRoute->method = $method;
       $newRoute->url = strtolower(trim($url, "/")); 
       $newRoute->controllerAction = "App\\Controllers\\$controllerAction";
       array_push(self::$routes, $newRoute);
    }
}