<?php
/**
 * Created by PhpStorm.
 * User: Sebas
 * Date: 04/12/2017
 * Time: 11:42
 */

namespace controllers;

use Ajax\JsUtils;
use micro\orm\DAO;
use micro\utils\RequestUtils;
use Ajax\semantic\html\elements\HtmlButton;

/**
 * Class EcranController
 * @package controllers
 * @property JsUtils $jquery
 */
class EcranController extends ControllerBase
{

    public function index() {

        $this->_printData();
        $this->jquery->compile($this->view);
        $this->loadview("sites/fondecran.html");
    }

    /*public function modifecran(){

        $semantic=$this->jquery->semantic();

        $form=$semantic->htmlForm("frm10");
        $form->addDropdown("country",array("Your Name","Ile","Japon"),"Country","",true);
        echo $form;

        echo $this->jquery->compile($this->view);
        $this->loadView("sites/fondecran.html");
    }*/

    public function modifecran(){

        $semantic=$this->jquery->semantic();

        $cards=$semantic->htmlCardGroups("card5",[
            ["Japon"],
            ["card6"]]);
        $cards->getCardContent(0,1)->addImage("http://localhost/homepage/assets/images/img6.jpg","")->setFloated("right");
        $cards->getCardContent(0,1)->addImage("http://localhost/homepage/assets/images/img7.jpg","")->setFloated("left");


        echo $cards;

        echo $cards->compile($this->jquery);
        echo $this->jquery->compile();


    }



}