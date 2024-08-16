<?php
    namespace Common;

    use Libs\Base\Container;
    use Libs\Http\Request;

    class Router {
        private $routes = [
            "guest" => [
                "middlewares" => [],
                "routes" => [

                ],
            ],
            "user" => [
                "middlewares" => [],
                "routes" => [

                ],
            ],
            "admin" => [
                "middlewares" => [],
                "routes" => [

                ],
            ],
        ];

        private $container;

        private $middlewares = [
            'auth' => 'MiddleWare\Auth',
        ];

        public function __construct(Container $container) {
            $this->container = $container;
        }
    
        private function add($route, $controller, $action, $method, $role) {
            $this->routes[$role]["routes"][] = [
                'route' => $route,
                'controller' => $controller,
                'action' => $action,
                'method' => $method,
            ];
        }

        private function addGroup(String $role, Array $middlewares, $callback) {
            foreach ($middlewares as $middleware) {
                $this->routes[$role]['middlewares'][] = $middleware;
            }
            $callback($role);
        }

        public function register() {
            $this->addGroup("guest", [], function($role){
                $this->add('', "Controller\LoginController", 'index', 'GET', $role);
                $this->add('login', "Controller\LoginController", 'index', 'GET', $role);
                $this->add('login', "Controller\LoginController", 'login', 'POST', $role);
                $this->add('logout', "Controller\LoginController", 'logout', 'GET', $role);
            });

            $this->addGroup("user", ["auth"], function($role){
                // 正規表現に一致した部分に名前をつけることで、パラメータを取得できる
                $this->add('top', "Controller\TopController", 'index', 'GET', $role);
                $this->add('top/(?P<id>\d+)', "Controller\TopController", 'detail', 'GET', $role);
            });

            $this->addGroup("admin", ["auth"], function($role){
                $this->add('admin/users', "Controller\Admin\UserController", 'index', 'GET', $role);
            });

        }
    
        public function dispatch(Request $request) {
            $uri = trim(parse_url($request->requestUri(), PHP_URL_PATH), '/');

            // .cssや.jsなどの静的ファイルはそのまま返す
            if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js)$/', $uri)) {
                return false;
            }

            $routeExist = false;
    
            foreach ($this->routes as $role => $routes) {
                foreach($routes["routes"] as $route) {
                    $regex = preg_replace('/\{(\w+):(\w+)\}/', '(?P<\1>\2)', $route['route']);
                    $regex = str_replace('/', '\/', $regex);
                    $regex = '/^' . $regex . '$/';

                    if (!preg_match($regex, $uri, $matches)) {
                        continue;
                    }

                    if ($request->methodType() !== $route['method']) {
                        continue;
                    }

                    // ミドルウェアの実行
                    foreach ($routes['middlewares'] as $middleware) {
                        $middlewareInstance = new $this->middlewares[$middleware];
                        $middlewareInstance->run();
                    }
                    
                    // 動的なURLの場合、パラメータを取得する
                    $params = [];
                    if (isset($matches['id'])) {
                        $params['id'] = $matches['id'];
                    }

                    $controllerName = $route['controller'];
                    $action = $route['action'];
                    $controller = $this->container->make($controllerName);

                    if (!method_exists($controller, $action)) {
                        throw new \Exception("Method $action not found in controller $controller");
                    }

                    // メソッドにDIしたい場合
                    $reflection = new \ReflectionMethod($controllerName, $action);
                    $parameters = $reflection->getParameters();
                    $dependencies = [];
                    foreach ($parameters as $parameter) {
                        $type = $parameter->getType();
                        if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                            $dependencies[] = $this->container->make($type->getName());
                        } else {
                            // ビルトイン型（例：int）や型ヒントなしの場合の処理
                            $dependencies[] = $params[$parameter->getName()];
                        }
                    }

                    $reflection->invokeArgs($controller, $dependencies);
                    $routeExist = true;
                }
            }

            if (!$routeExist) {
                echo "404 Not Found";
            }
        }
    }
?>