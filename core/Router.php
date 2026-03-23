<?php

class Router {
    private $routes = [];

    public function get($path, $action) {
        $this->addRoute('GET', $path, $action);
    }

    public function post($path, $action) {
        $this->addRoute('POST', $path, $action);
    }

    private function addRoute($method, $path, $action) {
        // Remplacer les paramètres éventuels {id} par une regex
        $pathRegex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $path);
        // Ajout des délimiteurs et ancres de regex
        $pathRegex = '@^' . $pathRegex . '/?$@'; 
        
        $this->routes[] = [
            'method' => $method,
            'path' => $pathRegex,
            'action' => $action
        ];
    }

    public function dispatch($uri, $method) {
        $parsedUrl = parse_url($uri);
        $path = $parsedUrl['path'] ?? '/';
        
        // Remove subfolder from path if application is not at web root
        // Adaptation basique selon l'environnement de dev local
        $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
            if (empty($path)) $path = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['path'], $path, $matches)) {
                array_shift($matches); // Enlever la correspondance complète
                
                list($controllerName, $methodName) = explode('@', $route['action']);
                
                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $methodName)) {
                        return call_user_func_array([$controller, $methodName], $matches);
                    }
                }
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo "404 - Page non trouvée";
        exit;
    }
}
