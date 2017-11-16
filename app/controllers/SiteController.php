<?php
namespace controllers;
use Ajax\JsUtils;
use micro\orm\DAO;
use models\Site;
use micro\utils\RequestUtils;
use models;
use Ajax\semantic\html\collections\form\HtmlFormInput;
use Ajax\semantic\widgets\business\user\FormLogin;
 
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
        $bts=$semantic->htmlButtonGroups("button-1",["Liste des favoris","Ajout d'un favoris","Modification des favoris"]);
        $bts->setPropertyValues("data-ajax",["printLien/", ""]);
        $bts->getOnClick("SiteController","#bt1",["attr"=>"data-ajax"]);
        $frm=$semantic->defaultLogin("frm2");
        $bts=$semantic->htmlButton("button-3","Se d&eacute;connecter","red");
        $frm=$semantic->defaultLogin("frm1");
        $frm->removeField("Connection");
        $frm->setCaption("forget", "Mot de passe oubli&eacute ?");
        $frm->setCaption("remember", "Se souvenir de moi.");
        $frm->setCaption("submit", "Connexion");
        $frm->setCaption("login", "Pseudo");
        $frm->setCaption("password", "Mot de passe");
        $frm->fieldAsSubmit("submit","green fluide","sTest/dePost","#frm1-submit");
        $bt=$semantic->htmlButton("button-2","Se connecter","green","$('#modal-frm1').modal('show');");
        $bt->addIcon("sign in");        
        echo $frm->asModal();        
        $this->jquery->exec("$('#modal-connect').modal('show');",true);
        echo $this->jquery->compile($this->view);
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
    
    private $idUser = 2;
    
    /**
     * @route("/liensweb")
     */
    public function printLien(){
        $semantic=$this->jquery->semantic();
        $liens=DAO::getAll("models\Lienweb","idUtilisateur=".$this->idUser);
        $table=$semantic->dataTable("tblLiens", "models\Lienweb", $liens);
        $table->setIdentifierFunction(function($i,$obj){return $obj->getId();});
        $table->setFields(["id","libelle","url"]);
        $table->setCaptions(["ID","Nom","URL","Actions"]);
        $table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]);
        $table->setTargetSelector("#bt1");
        echo $table->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    public function disconnected(){
        session_unset();
        session_destroy();
    }
    
    public function ajoutfav(){
        $form=$semantic->htmlForm("frm2");
        $form->addItem(new HtmlFormInput("ui","User input"));
        echo $form;
    }
    
    
}