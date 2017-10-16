<?php
namespace controllers;

use controllers\ControllerBase;

class ConnexionController extends ControllerBase
{
    /**
     * @route("/connexion_utilisateur")
     */
    public function index(){
        $this->loadView("connexion/index.html");
    }
    
}

