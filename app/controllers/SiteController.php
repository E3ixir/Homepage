<?php
namespace controllers;
use Ajax\JsUtils;
use micro\orm\DAO;
use models\Site;
use micro\utils\RequestUtils;
use models;
use Ajax\semantic\html\collections\form\HtmlFormInput;
 
/**
 * Controller SiteController
 * @property JsUtils $jquery
 **/
class SiteController extends ControllerBase{

    /**
     * @route("/all")
     */
    public function index(){
        $semantic=$this->jquery->semantic();
        $bts=$semantic->htmlButtonGroups("buttons",["Liste des sites","Ajouter un site...","Menu"]);
        $bts->setPropertyValues("data-ajax", ["all/","addUser/","menu/"]);
        $bts->getOnClick("","#divUsers",["attr"=>"data-ajax"]);
        $this->jquery->compile($this->view);
        $this->loadView("sites/index.html");
    }
    /**
     * @route("/all","cache"=>true,"duration"=>15)
     */
    public function all(){
        $users=DAO::getAll("models\Site");
        $semantic=$this->jquery->semantic();
        $table=$semantic->dataTable("tblSites", "models\Site", $users);
        $table->setFields(["id","nom","longitude"]);
        $table->setCaptions(["ID","Nom","Longitude","Actions"]);
        $table->addEditDeleteButtons(false);
        echo $table->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    /**
    *@route("menu/")
    */
    public function menu(){
        $menu=$semantic->htmlMenu("menu8");
        $menu->addMenuAsItem(["Enterprise","Consumer"],"Products");
        $menu->addMenuAsItem(["Rails","Python","PHP"],"CMS solutions");
        $menu->setVertical();
        echo $menu;
    }
}