<?php
namespace Controller;

use Repository\UserRepository;
use Libs\Base\Controller;
use Libs\DB\Transaction\Transaction;

class LoginController extends Controller {

    public $userRepository;
    public $transaction;

    public function __construct(
        UserRepository $userRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->transaction = new Transaction();
    }

    public function index() {
        $this->render('login', []);
    }

    public function login() {
        try {
            $user = $this->userRepository->findByUid($this->transaction->getPDO(), $_POST['uid']);

            if ($user === null) {
                return $this->redirect('/');
            }

        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        if (!password_verify($_POST['password'], $user->getPassword())) {
            return $this->redirect('/');
        }

        //パスワード確認後sessionにメールアドレスを渡す
        session_regenerate_id(true); //session_idを新しく生成し、置き換える
        $_SESSION['ID'] = $user->getId();
        $_SESSION['Rank'] = $user->getRank();

        return $this->redirect('/top');

    }

    public function logout() {
        session_destroy();
        return $this->redirect('/');
    }
}