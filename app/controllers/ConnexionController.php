<?php
namespace controllers;

use micro\orm\DAO;
use micro\utils\RequestUtils;
use models\Utilisateur;
use Ajax\semantic\html\collections\form\HtmlFormInput;

class ConnexionController extends ControllerBase
{
    /**
     * @route("/connexion_utilisateur")
     */
    public function index(){
        $semantic=$this->jquery->semantic();
        
        if(isset($_SESSION['user'])){
            header('Refresh: 0; url=/homepage/SiteController');
            ob_flush();
        }
        
        $bts=$semantic->htmlButtonGroups("buttons",["Connexion"]);
        $bts->setPropertyValues("data-ajax", ["/userConnection"]);
        $bts->getOnClick("","#divUsers",["attr"=>"data-ajax"]);
        $this->jquery->compile($this->view);
        $this->loadView("connexion/index.html");
    }
   
    /**
     * @route("/userRegister")
     */
    public function userRegister(){
        $user=new Utilisateur();
        RequestUtils::setValuesToObject($user,$_POST);
        $statut=DAO::getOne("models\Statut", 1);
        $user->setStatut($statut);
        $site=DAO::getOne("models\Site", 1);
        $user->setSite($site);
        if(DAO::insert($user)){
            echo $user->getLogin()." ajouté";
            echo "<br><br>";
            echo "Vous allez etre redirigé dans quelques secondes...";
            header('Refresh: 3; url=/homepage/SiteController');
            ob_flush();
        }
    }
    
}
    
?>

