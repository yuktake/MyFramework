<?php
namespace Controller\Admin;

use Repository\UserRepository;
use Libs\Base\Controller;
use Libs\DB\Transaction\Transaction;

class UserController extends Controller {

    public $userRepository;
    public $transaction;
    public $helper;

    public function __construct() {
        $this->userRepository = new UserRepository();
        $this->transaction = new Transaction();
    }

    public function index() {
        $rows = $this->userRepository->findAll($this->transaction->getPDO());

        $this->render('admin/users', [
            "rows" => $rows,
        ]);
    }
}