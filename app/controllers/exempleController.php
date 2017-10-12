<?php
namespace controllers;

use controllers\ControllerBase;

class ExempleController extends ControllerBase
{
    /**
     * @route("/test")
     */
    public function index(){
        $this->loadView("geoloc/exemple.php");
    }
    
}
