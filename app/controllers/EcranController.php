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
use Ajax\semantic\html\elements\HtmlButton;

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

        $card=$semantic->htmlCard("card4");
        $img=$card->addImage("http://localhost/homepage/assets/images/img4.jpg");
        $img->addDimmer(["on"=>"hover","opacity"=>0.5],HtmlButton::labeled("bt4","add friend","plus"))->setBlurring();
        $card->addItemHeaderContent("Kristy","Joined in 2013","Kristy is an art director living in New York.");
        $card->addExtraContent("22 Friends")->addIcon("user");

        echo $card->compile($this->jquery);
        echo $this->jquery->compile();

        $card=$semantic->htmlCard("card5");
        $img=$card->addImage("http://localhost/homepage/assets/images/img5.png");
        $img->addDimmer(["on"=>"hover","opacity"=>0.5],HtmlButton::labeled("bt5","add friend","plus"))->setBlurring();
        $card->addItemHeaderContent("Kristy","Joined in 2013","Kristy is an art director living in New York.");
        $card->addExtraContent("22 Friends")->addIcon("user");


        echo $card->compile($this->jquery);
        echo $this->jquery->compile();

        $card=$semantic->htmlCard("card6");
        $img=$card->addImage("http://localhost/homepage/assets/images/img6.jpg");
        $img->addDimmer(["on"=>"hover","opacity"=>0.5],HtmlButton::labeled("bt6","add friend","plus"))->setBlurring();
        $card->addItemHeaderContent("Kristy","Joined in 2013","Kristy is an art director living in New York.");
        $card->addExtraContent("22 Friends")->addIcon("user");


        echo $card->compile($this->jquery);
        echo $this->jquery->compile();
    }



}