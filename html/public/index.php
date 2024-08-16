<?php

    require_once '../Config/DirectorySettings.php';
    require_once '../Libs/AutoLoader.php';

    use Config\DirectorySettings;
    use Libs\AutoLoader;
    use Libs\Base\Container;
    use Libs\Http\Request;
    use Libs\Config\EnvLoader;
    use Common\Router;

    session_start();

    $auto_loader = new AutoLoader(DirectorySettings::APPLICATION_ROOT_DIR);
    $auto_loader->run();

    $env_loader = new EnvLoader();
    $env_loader->loadEnv();

    $container = new Container();
    $container->register();
    
    $router = new Router($container);
    $router->register();

    $request = Request::instance();
    $router->dispatch($request);
?>