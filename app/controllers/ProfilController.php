<?php
/**
 * Created by PhpStorm.
 * User: Sebas
 * Date: 21/11/2017
 * Time: 09:42
 */

namespace controllers;


class ProfilController
{
    public function index() {
        $semantic=$this->jquery->semantic();

        $btHome=$semantic->htmlButton("btHome","");
        $btHome->asIcon("home")->asLink("");

        $this->_printData();
        $this->jquery->compile($this->view);
        $this->loadview("profil/index.html");
    }

    private function _printData() {
        $id = $_SESSION['user']->getId();
        $semantic=$this->jquery->semantic();
        $user = DAO::getOne("models\Utilisateur",$id );
        $user->idSite=$user->getSite()->getId();
        $user->idStatut=$user->getStatut()->getId();
        $form=$semantic->dataForm("frmUser", $user);

        $form->setFields(["login","password","elementsMasques","fondEcran","couleur","ordre","idStatut","idSite","submit"]);
        $form->setCaptions(["Login","Password","Elements Masqués","Fond d'écran","Couleur","Ordre","Statut","Site","Valider"]);

        $form->fieldAsSubmit("submit","blue","ProfileController/updateUser/".$id,"#msgUpdate");

        $sites=DAO::getAll("models\Site");
        $form->fieldAsDropDown("idSite",JArray::modelArray($sites,"getId","getNom"));

        $status=DAO::getAll("models\Statut");
        $form->fieldAsDropDown("idStatut",JArray::modelArray($status,"getId","getLibelle"));
    }

    public function printData(){
        $this->_printData();
        $this->jquery->compile($this->view);
        $this->loadview("profil/index.html");
    }

    public function updateUser() {
        $id=1;
        $semantic=$this->jquery->semantic();
        $user = DAO::getOne("models\Utilisateur",$id );

        RequestUtils::setValuesToObject($user,$_POST);

        if(DAO::update($user)){
            echo $semantic->htmlMessage("msgUsers","".$user->getLogin()." modifié(e)");
        }
    }
}