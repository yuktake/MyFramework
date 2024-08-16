<?php
namespace Libs\Base;

use Libs\Http\Response;
use Libs\Views\View;

class Controller {
    protected function render($viewFileName, $data=[]){
        $view = new View();
        $response = new Response($view->render($viewFileName, $data));
        $response->send();
    }

    protected function redirect($uri) {
        return Response::redirect($uri);
    }
}