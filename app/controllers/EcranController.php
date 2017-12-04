<?php
/**
 * Created by PhpStorm.
 * User: Sebas
 * Date: 04/12/2017
 * Time: 11:42
 */

namespace controllers;

use micro\orm\DAO;
use micro\utils\RequestUtils;

class EcranController extends ControllerBase
{

    public function index() {

        $this->_printData();
        $this->jquery->compile($this->view);
        $this->loadview("sites/fondecran.html");
    }

    public function modifecran(){

        $semantic=$this->jquery->semantic();

        $form=$semantic->htmlForm("frm10");
        $form->addDropdown("country",array("Moi","je","mien"),"Country","",true);
        echo $form;

        echo $this->jquery->compile($this->view);
        $this->loadView("sites/fondecran.html");
    }

}