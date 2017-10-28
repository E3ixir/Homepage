<?php
namespace controllers;

use controllers\ControllerBase;

class StyleController extends ControllerBase
{

    public function index()
    {
        $this->loadView("main/style.css");
    }
}

