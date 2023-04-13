<?php
class Router {

    protected $routes = [];


    protected function addRoute ($method, $pattern, $handler)
    {
        $this->routes[] = [$method, $pattern, $handler];
    }

    public function loadRoutesFromJson()
    {       
        try{
            $jsonFile = file_get_contents( _RMODEL . 'routes.json');
            if(!$jsonFile){
                throw new Exception('Archivo routes.json no existe en el directorio model/');
            }
        }catch(Exception $e){
            $this->handlerError($e);
            die;
        }

        $routes = json_decode($jsonFile, true);

        foreach ($routes['routes'] as $route) {
            $this->addRoute($route['method'], $route['pattern'], $route['handler']);
        }
    }

    public function handleRequest() 
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $url = $this->SanitizeVar($_SERVER['REQUEST_URI']);
        $requestUrl = parse_url($url, PHP_URL_PATH);

        foreach ($this->routes as [$method, $pattern, $handler]) {
            if ($method !== $requestMethod) {
                continue;
            }
            $matches = [];
            if (preg_match($this->compileRouteRegex($pattern), $requestUrl, $matches)) {
                array_shift($matches); 
                $matches = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                if (is_callable($handler)) {
                    call_user_func_array($handler, $matches);
                    return;
                }

                list($controllerName, $methodName) = explode('@', $handler);
                require_once _RCONTROLLER . $controllerName . '.php'; 
                if (strpos($controllerName, 'admin/') !== false) {
                    $controllerName = str_replace('admin/', '', $controllerName);
                }
                $controller = new $controllerName();
                $controller->$methodName(...$matches);
                return;
            }
        }
        die;
    }

    protected function compileRouteRegex($pattern) {
        $regex = '#^' . preg_replace_callback('#{(\w+)}#', function($matches) {
            return '(?P<' . $matches[1] . '>[^/]+)';
        }, $pattern) . '/?$#';
        return $regex;
    }
        
    protected function SanitizeVar($var)
    {
        $var = urldecode($var);
        $var = htmlspecialchars($var, ENT_QUOTES);
        $var = preg_replace('/[^a-zA-Z0-9 \/\.=_~Ññáéíóúü-]/u', ' ', $var);
        return $var;
    }

    private function handlerError (Throwable $e)
    {
        $errorMessage = date('Y-m-d H:i:s') . ' : Error : ' . $e->getMessage() . "\n";
        error_log($errorMessage, 3, _ROOT_PATH . 'log/error.log');  
    }
}