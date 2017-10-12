<?php
namespace controllers;

class AdminController extends ControllerBase
{

    public function index()
    {
        $this->loadView("administration/index.html");
    }
}

