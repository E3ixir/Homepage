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
       
        $frm=$semantic->defaultLogin("frm2");
        $frm=$semantic->defaultLogin("frm1");
        $frm->removeField("Connection");
        $frm->removeField("remember");
        $frm->removeField("forget");
        $frm->setCaption("forget", "Mot de passe oubli&eacute ?");
        $frm->setCaption("remember", "Se souvenir de moi.");
        $frm->setCaption("submit", "Connexion");
        $frm->setCaption("login", "Pseudo");
        $frm->setCaption("password", "Mot de passe");
        $frm->fieldAsSubmit("submit","green fluide","SiteController/connected","#frm1-submit");
        
        if(!isset($_SESSION['user'])){
            $btCo=$semantic->htmlButton("button-2","Se connecter","green","$('#modal-frm1').modal('show');");
            $btCo->addIcon("sign in");
            $fondecran="http://localhost/homepage/assets/images/img8.jpeg";
        } elseif ($_SESSION["user"]->getStatut()->getLibelle() == "Super administrateur"){
            $user=$_SESSION["user"];
            $messCo=$semantic->htmlMessage("#btCo","Bienvenue ".$user->getLogin(),"blue");
            $messCo->setDismissable();
            $messCo->compile($this->jquery);

            $bt_deco=$semantic->htmlButton("button-3","Se d&eacute;connecter","red");
            $bt_deco->addIcon("sign out");
            $bt_deco->asLink("SiteController/disconnected");

            $bts=$semantic->htmlButtonGroups("button-1",["Détails personnels","Fond d'écran","Liste de vos favoris","Ajouter un favoris","Fermer"]);
            $bts->setPropertyValues("data-ajax",["ProfilController/","EcranController/modifecran","SiteController/printLien/","SiteController/ajoutfav/","SiteController/close"]);
            $bts->getOnClick("","#list-site",["attr"=>"data-ajax"]);
            $bt_admin = $semantic->htmlButton("btAdmin","Administration","purple");
            $bt_admin->addIcon("settings");
            $bt_admin->asLink("AdminController");
            $fondecran=$_SESSION['user']->getFondEcran();

        }elseif (isset($_SESSION["user"])) {
            $user = $_SESSION["user"];
            $messCo = $semantic->htmlMessage("#btCo", "Bienvenue " . $user->getLogin(), "blue");
            $messCo->setDismissable();
            $messCo->compile($this->jquery);

            $bt_deco=$semantic->htmlButton("button-3","Se d&eacute;connecter","red");
            $bt_deco->addIcon("sign out");
            $bt_deco->asLink("SiteController/disconnected");

            $bts = $semantic->htmlButtonGroups("button-1", ["Détails personnels", "Fond d'écran", "Liste de vos favoris", "Ajouter un favoris", "Fermer"]);
            $bts->setPropertyValues("data-ajax", ["ProfilController/", "EcranController/modifecran", "SiteController/printLien/", "SiteController/ajoutfav/", "SiteController/close"]);
            $bts->getOnClick("", "#list-site", ["attr" => "data-ajax"]);
            $fondecran=$_SESSION['user']->getFondEcran();
        }

        echo $frm->asModal();

        $this->jquery->exec("$('#modal-connect').modal('show');",true);

        $this->jquery->exec("$('body').attr('style','background: url(".$fondecran.") no-repeat fixed; background-size: cover;');",true);

        echo $this->jquery->compile($this->view);
        $this->loadView("sites/index.html");
    }

    /**/
    public function connected(){
        $semantic=$this->jquery->semantic();
        $user=DAO::getOne("models\Utilisateur","login='".$_POST['login']."'");
        if(isset($user)){
            if($user->getPassword()===$_POST['password']){
                $_SESSION["user"]=$user;
                $this->jquery->get("SiteController/index", "body");
                $messCo=$semantic->htmlMessage("#btCo","Bienvenue ".$user->getLogin(),"blue");
                $messCo->setDismissable();
                echo $messCo->compile($this->jquery);

            } else {
                echo $semantic->htmlMessage("#btCo","Erreur, votre mot de passe ou login est incorrecte.","red");
            }
        } else {
            echo $semantic->htmlMessage("#btCo","Erreur, votre mot de passe ou login est incorrecte.","red");
        }
        echo $this->jquery->compile($this->view);
    }
    
    public function disconnected(){
        session_unset();
        session_destroy();
        header("location:/homepage/SiteController");
        $this->jquery->get("SiteController/index", "body");
        echo $this->jquery->compile($this->view);
    }
    
    
    /**
     * @route("/liensweb")
     */
    public function printLien(){
        $semantic=$this->jquery->semantic();
        
        $user=$_SESSION["user"];
        $links=DAO::getAll("models\Lienweb","idUtilisateur='".$user->getId()."'");
        
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
    
    private function _ajoutfav(){
        $semantic=$this->jquery->semantic();
        
        $link=new Lienweb();
        
        $form=$semantic->dataForm("frm3", $link);
        
        $form->setFields(["libelle\n","url\n","ordre\n","submit"]);
        $form->setCaptions(["Site internet","URL","Ordre","Valider"]);
        $form->fieldAsInput(0,["jsCallback"=>function($input){$input->setWidth(8);}]);
        $form->fieldAsInput(1,["jsCallback"=>function($input){$input->setWidth(8);}]);
        $form->fieldAsInput(2,["jsCallback"=>function($input){$input->setWidth(8);}]);
        
        $form->fieldAsSubmit("submit","orange","SiteController/new","#list-site");
    }
    
    public function ajoutfav() {
        $this->_ajoutfav();
        $this->jquery->compile($this->view);
        $this->loadView("sites/editfav.html");
    }
    
    public function nouvelle() {
        $semantic=$this->jquery->semantic();
        $link=new Lienweb();
        $user=$_SESSION["user"];
        
        
        RequestUtils::setValuesToObject($link,$_POST);
        
        $select_user=DAO::getOne("models\Utilisateur", $user->getId());
        $link->setUtilisateur($select_user);
        
        if(DAO::insert($link)){
            echo $semantic->htmlMessage("#list-site",$link->getLibelle()." ajout&eacute;");
        }
    }
    
    private function _modiffav($id){
        $semantic=$this->jquery->semantic();
        
        $fav=DAO::getOne("models\Lienweb", $id);
        
        $form=$semantic->dataForm("frm3", $fav);
        
        $form->setFields(["id","libelle","url","ordre","submit"]);
        $form->setCaptions(["id","Libelle","URL","Ordre","Valider"]);
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