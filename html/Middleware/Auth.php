<?php
namespace Middleware;

class Auth {
    public function run() {
        if (!isset($_SESSION["ID"])) {
            header('Location: /', true, 301);
            exit;
        }
    }
}