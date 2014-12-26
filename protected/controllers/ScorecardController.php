<?php

namespace org\csflu\isms\controllers;

use org\csflu\isms\core\Controller;

class ScorecardController extends Controller {
    
    public function __construct() {
        $this->checkAuthorization();
    }
    
    public function index(){
        
    }
}
