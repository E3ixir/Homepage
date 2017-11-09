<?php
namespace controllers;

use controllers\ControllerBase;
use micro\orm\DAO;
use micro\utils\RequestUtils;
use models\Utilisateur;

class ConnexionController extends ControllerBase
{
    /**
     * @route("/connexion_utilisateur")
     */
    public function index(){
        $this->loadView("connexion/index.html");
    }
    
    public function userConnection(){
        $user=DAO::getOne("models\Utilisateur", "login='".$_POST["login"]."'");
        if(isset($user)){
            
        }
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

