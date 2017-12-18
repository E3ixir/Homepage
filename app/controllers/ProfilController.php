<?php
/**
 * Created by PhpStorm.
 * User: Sebas
 * Date: 04/12/2017
 * Time: 11:17
 */

namespace controllers;


use micro\orm\DAO;
use micro\utils\RequestUtils;

class ProfilController extends ControllerBase
{
    public function index() {

        $this->_printData();
        $this->jquery->compile($this->view);
        $this->loadview("sites/detpers.html");
    }

    private function _printData() {
        $id = $_SESSION['user']->getId();
        $semantic=$this->jquery->semantic();
        $user = DAO::getOne("models\Utilisateur",$id );
        $form=$semantic->dataForm("frmUser", $user);

        $form->setFields(["login","password","elementsMasques","fondecran","couleur","ordre","submit"]);
        $form->setCaptions(["Login","Password","Elements Masqués","Fond d'écran (insérer votre lien web)","Couleur","Ordre","Valider"]);

        $form->fieldAsSubmit("submit","orange","ProfilController/updateUser/".$id,"#msgUpdate");
    }

    public function printData(){
        $this->_printData();
        $this->jquery->compile($this->view);
        $this->loadview("sites/detpers.html");
    }

    public function updateUser() {
        $semantic=$this->jquery->semantic();

        $user = DAO::getOne("models\Utilisateur",$_SESSION['user']->getId());

        RequestUtils::setValuesToObject($user,$_POST);

        if(DAO::update($user)){
            echo $semantic->htmlMessage("msgUsers","".$user->getLogin()." modifié(e)");
        }
    }

}