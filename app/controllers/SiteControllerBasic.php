<?php
namespace controllers;
use Ajax\JsUtils;
use micro\orm\DAO;
use models\Lienweb;
use models\Site;
use micro\utils\RequestUtils;
use models;
use Ajax\semantic\html\collections\form\HtmlFormInput;
use Ajax\semantic\widgets\business\user\FormLogin;
use Ajax\semantic\widgets\dataform\DataForm;
use Ajax\bootstrap\html\HtmlForm;

/**
 * Controller SiteControllerBasic
 * @property JsUtils $jquery
 **/
class SiteControllerBasic extends ControllerBase{
    
    /**
     * @route("/all")
     */
    public function index(){
        $semantic=$this->jquery->semantic();
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
        $this->loadView("sites/indexBasic.html");
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
        $table->addDeleteButton(false);
        echo $table->compile($this->jquery);
        echo $this->jquery->compile();
    }
    

    
    public function disconnected(){
        session_unset();
        session_destroy();
    }

   
    
 
}