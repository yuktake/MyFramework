<?php

namespace Libs\Base;

use Controller\LoginController;
use Controller\TopController;
use Controller\Admin\UserController;
use Repository\UserRepository;

class Container {
    private $bindings = [];

    private function bind(string $abstract, callable $concrete) {
        $this->bindings[$abstract] = $concrete;
    }

    public function make(string $abstract) {
        if (!isset($this->bindings[$abstract])) {
            throw new \Exception("No binding found for $abstract");
        }

        return $this->bindings[$abstract]($this);
    }

    public function register() {
        if (ENV === 'prod') {
            $this->prod_di();
        } else {
            $this->develop_di();
        }
    }

    private function prod_di() {
        // Classに対してのDI
        $this->bind(UserRepository::class, function($container) {
            return new UserRepository();
        });
    
        $this->bind(LoginController::class, function($container) {
            return new LoginController(
                $container->make(UserRepository::class)
            );
        });
        $this->bind(TopController::class, function($container) {
            return new TopController();
        });
        $this->bind(UserController::class, function($container) {
            return new UserController();
        });
    }

    private function develop_di() {
        // Classに対してのDI
        $this->bind(UserRepository::class, function($container) {
            return new UserRepository();
        });
    
        $this->bind(LoginController::class, function($container) {
            return new LoginController(
                $container->make(UserRepository::class)
            );
        });
        $this->bind(TopController::class, function($container) {
            return new TopController();
        });
        $this->bind(UserController::class, function($container) {
            return new UserController();
        });
    }
}