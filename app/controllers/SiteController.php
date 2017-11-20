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
 * Controller SiteController
 * @property JsUtils $jquery
 **/
class SiteController extends ControllerBase{

    /**
     * @route("/all")
     */
    public function index(){
        $semantic=$this->jquery->semantic();
        $bts=$semantic->htmlButtonGroups("button-1",["Liste des favoris","Ajout d'un favoris","Fermer"]);
        $bts->setPropertyValues("data-ajax",["printLien/","ajoutfav/","close/"]);
        $bts->getOnClick("SiteController","#list-site",["attr"=>"data-ajax"]);
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
        $table->addDeleteButton(false);
        echo $table->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    
    /**
     * @route("/liensweb")
     */
    public function printLien(){
        $links=DAO::getAll("models\Lienweb");
        
        $semantic=$this->jquery->semantic();
        
        $table=$semantic->dataTable("tblLinks", "models\Lienweb", $links);
        
        $table->setIdentifierFunction(function($i,$o){return $o->getId();});
        $table->setFields(["libelle","url","ordre"]);
        $table->setCaptions(["Site","URL","Ordre"]);
        
        $table->addEditButton(false);
        $table->addDeleteButton(false);
        $table->setUrls(["edit"=>"SiteController/modiffav","delete"=>"SiteController/delete"]);
        $table->setTargetSelector("#list-site");
        echo $table->compile($this->jquery);
        echo $this->jquery->compile();
    }
    
    public function disconnected(){
        session_unset();
        session_destroy();
    }
    
    private function _ajoutfav(){
        $semantic=$this->jquery->semantic();
        
        $link=new Lienweb();
        $link->idSite="";
        
        $form=$semantic->dataForm("frm3", $link);
        
        $form->setFields(["libelle\n","url","ordre","submit"]);
        $form->setCaptions(["Site internet","URL","Ordre","Valider"]);
       
        
        $form->fieldAsSubmit("submit","blue","SiteController/new","#list-site");
    }
    
    public function ajoutfav() {
        $this->_ajoutfav();
        $this->jquery->compile($this->view);
        $this->loadView("sites/editfav.html");
    }
    
    public function new() {
        $semantic=$this->jquery->semantic();
        $link=new Lienweb();
        
        RequestUtils::setValuesToObject($link,$_POST);
        
        
        if(DAO::insert($link)){
            echo $semantic->htmlMessage("#list-site",$link->getLibelle()." ajout&eacute;");
        }
    }
    
    private function _modiffav($id){
        $semantic=$this->jquery->semantic();
        
        $fav=DAO::getOne("models\Lienweb", $id);
        
        $form=$semantic->dataForm("frm3", $fav);
        
        $form->setFields(["id","libelle","url","submit"]);
        $form->setCaptions(["id","Libelle","URL","Valider"]);
        $form->fieldAsHidden("id");
        $form->fieldAsSubmit("submit","yellow","SiteController/updatefav","#list-site");
    }
    
    public function modiffav($id){
        $this->_modiffav($id);
        $this->jquery->compile($this->view);
        $this->loadView("sites/editfav.html");
    }
    
    public function updatefav(){
            $semantic=$this->jquery->semantic();
            $liens = DAO::getOne("models\Lienweb", $_POST["id"]);
            RequestUtils::setValuesToObject($liens,$_POST);
            
            if(DAO::update($liens)) {
                echo $semantic->htmlMessage("#bt1",$liens->getLibelle()." modifi&eacute;");
            }
            
    }
    
    public function delete($id) {
        $semantic=$this->jquery->semantic();
        $link = DAO::getOne("models\Lienweb",$id );
        
        if(DAO::remove($link)) {
            echo $semantic->htmlMessage("#bt1",$link->getLibelle()." supprim&eacute;");
        }
    }
    
    public function close(){
        
    }
            
}