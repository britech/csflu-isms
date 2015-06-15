<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;

/**
 * Description of UtilityController
 *
 * @author britech
 */
class UtilityController extends Controller {

    public function __construct() {
        $this->layout = "simple";
    }

    public function hashPassword($clearPassword = null) {
        $password = is_null($clearPassword) ? "Password@123" : $clearPassword;
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->render('utility/password', array(
            'clearPassword' => $password,
            'hashedPassword' => $hashedPassword
        ));
    }

}
