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

    /*
     * Fonction index()
     * Elle va afficher les éléments de base de la page
     * Elle contient la création plus l'affichage du formulaire de connexion, du menu utilisateur, de la barre de recherche et des boutons connexion/déconnexion
     * Tous ces éléments ne s'affichent pas à la fois, il y a des conditions
     * On vérifie entre autre si l'utilisateur est connecté ou non, et si oui, s'il est administrateur ou simple utilisateur
     * La variable $fondecran est définit par un lien de base si l'utilisateur n'est pas connecté, et change selon le choix de l'utilisateur
     */
    public function index(){
        $semantic=$this->jquery->semantic();

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
            
            
            
        } elseif($_SESSION["user"]->getStatut()->getLibelle() == "Super administrateur") {
            $user=$_SESSION["user"];
            $messCo=$semantic->htmlMessage("#btCo","Bienvenue ".$user->getLogin(),"blue");

            $bt_deco=$semantic->htmlButton("button-3","Se d&eacute;connecter","red");
            $bt_deco->addIcon("sign out");
            $bt_deco->asLink("SiteController/disconnected");

            $bts=$semantic->htmlButtonGroups("button-1",["Détails personnels","Liste de vos favoris","Ajouter un favoris","Fermer"]);
            $bts->setPropertyValues("data-ajax",["ProfilController/","SiteController/printLien/","SiteController/ajoutfav/","SiteController/close"]);
            $bts->getOnClick("","#list-site",["attr"=>"data-ajax"]);
            $bt_admin = $semantic->htmlButton("btAdmin","Administration","purple");
            $bt_admin->addIcon("settings");
            $bt_admin->asLink("AdminController");
            $fondecran=$_SESSION['user']->getFondEcran();

        }elseif (isset($_SESSION["user"])) {
            $user = $_SESSION["user"];
            $messCo=$semantic->htmlMessage("#btCo", "Bienvenue " . $user->getLogin(), "blue");

            $bt_deco=$semantic->htmlButton("button-3","Se d&eacute;connecter","red");
            $bt_deco->addIcon("sign out");
            $bt_deco->asLink("SiteController/disconnected");

            $bts = $semantic->htmlButtonGroups("button-1", ["Détails personnels", "Liste de vos favoris", "Ajouter un favoris", "Fermer"]);
            $bts->setPropertyValues("data-ajax", ["ProfilController/", "SiteController/printLien/", "SiteController/ajoutfav/", "SiteController/close"]);
            $bts->getOnClick("", "#list-site", ["attr" => "data-ajax"]);
            $fondecran=$_SESSION['user']->getFondEcran();
        }

        echo $frm->asModal();

        $this->jquery->exec("$('#modal-connect').modal('show');",true);

        $this->jquery->exec("$('body').attr('style','background: url(".$fondecran.") no-repeat fixed; background-size: cover;');",true);

        echo $this->jquery->compile($this->view);
        $this->loadView("sites/index.html");
    }
    
    /*
     * Fonction connected()
     * La fonction vérifie d'abord si les identifiants rentrés sont corrects
     * S'ils le sont, la page est actualisée, un message de bienvenue s'affiche et l'utilisateur a accès à son menu personnel
     * Si le nom d'utilisateur ou le mot de passe est incorrect, un message d'erreur s'affiche
     */
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
    
    /* 
     * Fonction disconnected()
     * Cette fonction se déclenche lorsque l'on clique sur le bouton Déconnexion
     * Elle détruit la session en cours puis actualise la page afin de permettre une nouvelle connexion pour un autre utilisateur
     */
    public function disconnected(){
        session_unset();
        session_destroy();
        header("location:/homepage/SiteController");
        $this->jquery->get("SiteController/index", "body");
        echo $this->jquery->compile($this->view);
    }
    
    
    /*
     * Fonction printLien()
     * Cette fonction affiche tous les sites favoris de l'utilisateur connecté dans un tableau de données
     * On retrouve ces liens grâce à l'ID de l'utilisateur
     * On y affiche pour chaque site son nom et son URL
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
    
    /*
     * Fonction _ajoutfav()
     * Elle permet la création d'un nouveau site favoris propre à l'utilisateur
     * Pour cela, création d'une dataForm
     * On doit y entrer comme informations son nom, son URL ainsi que le numéro de la position dont on souhaite qu'il apparaisse dans le tableau de données précédent
     */
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
    
    /*
     * Fonction ajoutfav()
     * Cette fonction appelle le fichier HTML affichant le formulaire créé précédemment
     */
    public function ajoutfav() {
        $this->_ajoutfav();
        $this->jquery->compile($this->view);
        $this->loadView("sites/editfav.html");
    }
    
    /*
     * Fonction nouvelle()
     * Cette fonction ajoute le site précédent dans la base de données
     * Elle s'exécute lors du clique sur le bouton Valider
     */
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
    
    /*
     * Fonction _modiffav(id)
     * Elle permet la modification d'un site favoris propre à l'utilisateur
     * Pour cela, création d'une dataForm
     * On peut modifier comme informations son nom, son URL ainsi que le numéro de la position dont on souhaite qu'il apparaisse dans le tableau de données précédent
     */
    private function _modiffav($id){
        $semantic=$this->jquery->semantic();
        
        $fav=DAO::getOne("models\Lienweb", $id);
        
        $form=$semantic->dataForm("frm3", $fav);
        
        $form->setFields(["id","libelle","url","ordre","submit"]);
        $form->setCaptions(["id","Libelle","URL","Ordre","Valider"]);
        $form->fieldAsHidden("id");
        $form->fieldAsSubmit("submit","yellow","SiteController/updatefav","#list-site");
    }
    
    /*
     * Fonction modiffav(id)
     * Cette fonction appelle le fichier HTML affichant le formulaire créé précédemment
     * On modifie les informations d'un site en allant sur l'onglet "Liste de vos favoris" puis en cliquant sur le carré gris
     */
    public function modiffav($id){
        $this->_modiffav($id);
        $this->jquery->compile($this->view);
        $this->loadView("sites/editfav.html");
    }
    
    /*
     * Fonction updatefav()
     * Cette fonction apporte les modification du site précédent dans la base de données
     * Elle s'exécute lors du clique sur le bouton Valider
     */
    public function updatefav(){
            $semantic=$this->jquery->semantic();
            $liens = DAO::getOne("models\Lienweb", $_POST["id"]);
            RequestUtils::setValuesToObject($liens,$_POST);
            
            if(DAO::update($liens)) {
                echo $semantic->htmlMessage("#bt1",$liens->getLibelle()." modifi&eacute;");
            }
            
    }
    
    /*
     * Fonction delete(id)
     * Cette fonction permet simplement de supprimer le site sélectionné
     * La fonction s'exécute lors du clique sur le bouton avc la croix rouge
     */
    public function delete($id) {
        $semantic=$this->jquery->semantic();
        $link = DAO::getOne("models\Lienweb",$id );
        
        if(DAO::remove($link)) {
            echo $semantic->htmlMessage("#bt1",$link->getLibelle()." supprim&eacute;");
        }
    }
    
    /*
     * Fonction close()
     * Elle permet simplement de rendre vide la partie se situant en dessous du menu utilisateur, comme lorsque celui-çi vient de se connecter
     */
    public function close(){
        
    }
}