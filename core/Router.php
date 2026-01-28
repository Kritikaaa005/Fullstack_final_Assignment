<?php

class Router
{
    private $routes = [];

    public function add($method, $path, $callback)
    {
        $path = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[^/]+)', $path);
        $this->routes[] = [
            'method' => $method,
            'path' => "#^" . $path . "$#",
            'callback' => $callback
        ];
    }

    public function get($path, $callback)
    {
        $this->add('GET', $path, $callback);
    }

    public function post($path, $callback)
    {
        $this->add('POST', $path, $callback);
    }

    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove base path if project is not in root
        $scriptUrl = $_SERVER['SCRIPT_NAME'];
        $basePath = str_replace('/index.php', '', $scriptUrl);

        if ($basePath !== '' && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }

        if ($path === '' || $path === false) {
            $path = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['path'], $path, $matches)) {
                $callback = $route['callback'];

                if (is_array($callback)) {
                    $controller = $callback[0];
                    $action = $callback[1];

                    // Filter matches to keep only named parameters
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                    return call_user_func_array([$controller, $action], $params);
                }

                if (is_callable($callback)) {
                    return call_user_func($callback);
                }
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo "404 Not Found";
    }
}
