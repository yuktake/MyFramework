<?php
namespace Controller;

use Libs\Base\Controller;

class TopController extends Controller {

    public $helper;

    public function __construct() {

    }

    public function index() {

        return $this->render('users/top', [

        ]);
    }

    public function detail(
        int $id
    ) {
        return $this->render('users/detail', [
            "id" => $id,
        ]);
    }
}