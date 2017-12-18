<?php

namespace controllers;
use Ajax\JsUtils;
use micro\orm\DAO;
use micro\utils\RequestUtils;
use models;
use models\Site;
use models\Utilisateur;
use Ajax\semantic\widgets\datatable\DataTable;

/**
 * Controller AdminController
 * @property JsUtils $jquery
 **/

class AdminController extends ControllerBase{

    /**
     * statut de la connexion
     * l'administration du site n'est possible que si c'est un super admin
     **/
    public function isValid()
    {
        if (!isset($_SESSION['user'])){
           return false;
        } elseif ($_SESSION['user']->getStatut()->getLibelle() != "Super administrateur"){
            return false;
        } else {
            return true;
        }
    }
    /**
     * retour site principal
     **/

    public function onInvalidControl()
    {
        header("location:/homepage/SiteController");
    }

    /**
     * fonction principale avec l'affichage de la barre
     **/
    public function index(){

        $semantic=$this->jquery->semantic();
        $bts=$semantic->htmlButtonGroups("bts",["Liste des sites","Ajout d'un site","Liste des utilisateurs","Ajout d'un utilisateur","Reinitialiser"]);
        $bts->setPropertyValues("data-ajax", ["allSite/","addSite/","allUti/","addUti","close/"]);
        $bts->getOnClick("AdminController/","#divSites",["attr"=>"data-ajax",]);
        $bt_retour = $semantic->htmlButton("btRetour","Site","brown");
        $bt_retour->addIcon("settings");
        $bt_retour->asLink("SiteController");
        $this->jquery->compile($this->view);
        $this->loadView("Admin/index.html");

    }

    /**
     * affichage de tous les champs
     * affichage de toutes les données présentes dans la BDD
     **/
    private function toutSite(){

        $sites=DAO::getAll("models\Site");
        $semantic=$this->jquery->semantic();
        $table=$semantic->dataTable("tblSites", "models\Site", $sites);
        $table->setIdentifierFunction(function($i,$obj){return $obj->getId();});
        $table->setFields(["id","nom","latitude","longitude","ecart","fondEcran","couleur","ordre","options"]);
        $table->setCaptions(["Id","Nom","Latitude","Longitude","Ecart","Lien web","Couleur", "Ordre", "Options", "Actions"]);
        $table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]);
        $table->setUrls(["","AdminController/editSite","AdminController/deleteSite"]);
        $table->setTargetSelector("#divSites");

        echo $table;
        echo $this->jquery->compile();
    }

    /**
     * lance l'html de la page en admin
     **/
    public function allSite() {

        $this->toutsite();
        $this->jquery->compile($this->view);
        $this->loadView("Admin\index.html");
    }

    /**
     * affichage des données utilisateurs dans la BDD
     **/
    private function toutUti(){

        $user=DAO::getAll("models\Utilisateur");
        $semantic=$this->jquery->semantic();
        $table=$semantic->dataTable("tblutilisateur", "models\Utilisateur", $user);
        $table->setIdentifierFunction(function($i,$obj){return $obj->getId();});
        $table->setFields(["login","password","elementsMasques","fondecran","couleur","ordre"]);
        $table->setCaptions(["Login","Password","Elements Masqués","Lien web","Couleur","Ordre"]);
        $table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]);
        $table->setUrls(["","AdminController/editUti","AdminController/deleteUti"]);
        $table->setTargetSelector("#divSites");

        echo $table;
        echo $this->jquery->compile();
    }

    /**
     * appel de la fonction dans la page html
     **/
    public function allUti() {

        $this->toututi();
        $this->jquery->compile($this->view);
        $this->loadView("Admin\index.html");
    }

    /**
     * ajout d'un site dans la BDD
     * spécification de sa latitude et longitude
     **/
    public function addSite(){
        $this->_form(new Site(),"AdminController/newSite/",49.201491,-0.380734);
    }

    /**
     * ajout d'un utilisateur dans la BDD
     **/
    public function addUti(){
        $this->_formi(new Utilisateur(),"AdminController/newUti/");
    }

    /**
     * lancement de la form
     * champs de modification des données
     **/
    private function _form($site,$action,$lat,$long){

        $semantic=$this->jquery->semantic();
        $semantic->setLanguage("fr");
        $form=$semantic->dataForm("frmSite", $site);
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        $form->setFields(["nom\n","latitude","longitude","ecart\n","fondEcran\n","couleur\n","ordre\n","options\n","submit"]);
        $form->setCaptions(["Nom","Latitude","Longitude","Ecart","Lien web","Couleur", "Ordre", "Options","Valider"]);
        $form->fieldAsInput(0,["jsCallback"=>function($input){$input->setWidth(8);}]);
        $form->fieldAsInput(1,["jsCallback"=>function($input){$input->setWidth(3);}]);
        $form->fieldAsInput(2,["jsCallback"=>function($input){$input->setWidth(3);}]);
        $form->fieldAsInput(3,["jsCallback"=>function($input){$input->setWidth(2);}]);
        $form->fieldAsInput(4,["jsCallback"=>function($input){$input->setWidth(8);}]);
        $form->fieldAsInput(5,["jsCallback"=>function($input){$input->setWidth(8);}]);
        $form->fieldAsInput(6,["jsCallback"=>function($input){$input->setWidth(8);}]);
        $form->fieldAsInput(7,["jsCallback"=>function($input){$input->setWidth(8);}]);
        $form->fieldAsSubmit("submit","blue",$action,"#divSites");
        /*$this->jquery->click("#map","
         console.log(event);
         var latlong = event.latLng;
         var lat = latlong.lat();
         var long = latlong.lng();
         alert(lat+' - '+lng);
         ");*/
        //$this->jquery->change("[name=latitude]","alert('lat change : '+event.target.value);");
        $this->loadView("Admin\index.html",["jsMap"=>$this->_generateMap($lat,$long)]);

        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }

    /**
     * ajout et modification des paramètres utilisateur
     **/
    private function _formi($user,$action){

        $semantic=$this->jquery->semantic();
        $semantic->setLanguage("fr");
        $form=$semantic->dataForm("frmSite", $user);
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        $form->setFields(["login\n","password\n","fondEcran\n","Elements Masqués\n","Lien web\n","Couleur\n","Ordre\n","submit"]);
        $form->setCaptions(["Login","Password","Lien web","Couleur", "Ordre", "Options","Valider"]);
        $form->fieldAsInput(0,["jsCallback"=>function($input){$input->setWidth(8);}]);
        $form->fieldAsInput(1,["jsCallback"=>function($input){$input->setWidth(3);}]);
        $form->fieldAsInput(2,["jsCallback"=>function($input){$input->setWidth(3);}]);
        $form->fieldAsInput(3,["jsCallback"=>function($input){$input->setWidth(2);}]);
        $form->fieldAsInput(4,["jsCallback"=>function($input){$input->setWidth(8);}]);
        $form->fieldAsInput(5,["jsCallback"=>function($input){$input->setWidth(8);}]);
        $form->fieldAsInput(6,["jsCallback"=>function($input){$input->setWidth(8);}]);
        $form->fieldAsInput(7,["jsCallback"=>function($input){$input->setWidth(8);}]);
        $form->fieldAsSubmit("submit","blue",$action,"#divSites");

        echo $form->compile($this->jquery);
        echo $this->jquery->compile();
    }

    /**
     * fonction qui ajoute un nouveau site et validation
     **/
    public function newSite(){

        $site=new Site();
        RequestUtils::setValuesToObject($site,$_POST);
        if(DAO::insert($site)){
            echo "Le site ".$site->getNom()." a bien été ajouté.";
        }
    }

    /**
     * ajout d'un nouvel utilisateur et validation
     **/
    public function newUti(){

        $user=new Utilisateur();
        RequestUtils::setValuesToObject($user,$_POST);
        if(DAO::insert($user)){
            echo "L'utilisateur ".$user->getNom()." a bien été ajouté.";
        }
    }

    /**
     * supression d'un site dans la BDD
     **/
    public function deleteSite($id){
        //  if(RequestUtils::isPost())
        //{
        //echo " - ".$id." - ";
        $site=DAO::getOne("models\Site", $id);
        $site instanceof models\Site && DAO::remove($site);
        $this->forward("controllers\AdminController","allSite");
        //echo "le site {$site} a bien été supprimé.";
        /*if($site instanceof models\Site && DAO::remove($site))
         {
         echo "le site {$site} a bien été supprimé.";
         }else{ echo "impossible a supp";}*/
        //}
        //else{echo "Vous n'êtes pas autorisé à vous rendre ici.";}
    }

    /**
     * supression d'un utlisateur de la BDD
     **/
    public function deleteUti($id){
        $semantic=$this->jquery->semantic();
        $user=DAO::getOne("models\Utilisateur", $id);
        
        if(DAO::remove($user)) {
            echo $semantic->htmlMessage("#container-admin",$user->getLogin()." supprim&eacute;");
        }
        //echo "l'utilisateur {$user} a bien été supprimé.";
        /*if($site instanceof models\Site && DAO::remove($site))
         {
         echo "l'utilisateur {$user} a bien été supprimé.";
         }else{ echo "impossible a supp";}*/
        //}
        //else{echo "Vous n'êtes pas autorisé à vous rendre ici.";}
    }

    /**
     * retour du site
     **/
    public function _getSiteInGet(){
        //if(RequestUtils::isPost())
        {
            $id=RequestUtils::get('id');
            $site=DAO::getOne("models\Site", $id);
            if($site instanceof models\Site)
                return $site;
            return false;
        }
        /*else
         {
         return false;
         }*/
    }

    /**
     * formulaire d'édition du site
     **/
    public function editSite($id){
        $site=DAO::getOne("models\Site", $id);
        $this->_form($site,"AdminController/updateSite/".$id,$site->getLatitude(),$site->getLongitude());
    }

    /**
     * formulaire d'édition de l'utilisateur
     **/
    public function editUti($id){
        $user=DAO::getOne("models\Utilisateur", $id);
        $this->_formi($user,"AdminController/updateUti/".$id,$user);
    }

    /**
     * mise à jour d'un site
     **/
    public function updateSite($id){
        $semantic=$this->jquery->semantic();
        $site=DAO::getOne("models\Site", $id);
        RequestUtils::setValuesToObject($site,$_POST);
        if(DAO::update($site)){
            echo $semantic->htmlMesaage("container-admin","Le site ".$site->getNom()." a été modifié.");
        }
    }

    /**
     * mise à jour de l'utilisateur
     **/
    public function updateUti($id){
        $semantic=$this->jquery->semantic();
        $user=DAO::getOne("models\Utilisateur", $id);
        RequestUtils::setValuesToObject($user,$_POST);
        if(DAO::update($user)){
            echo $semantic->htmlMessage("container-admin","L'utilisateur ".$user->getLogin()." a été modifié.");
        }
    }

    /**
     * fonction de la carte
     * placement d'un marqueur sur la carte
     * envoie des données dans les emplacements latitude et longitude
     **/
    private function _generateMap($lat,$long){
        return "
      <script>

                var map;
                var markers = [];

                function initMap() {
                    var Ursulette = {lat: 49.182863, lng: -0.370679};

                    map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 12,
                        center: Ursulette,
                        mapTypeId: 'terrain'
                    });

                    /*On appelle le listener addMarker quand il y a un clique*/
                    map.addListener('click', function(event) {
                        addMarker(event.latLng);
                        document.getElementById('frmSite-latitude-0').value=event.latLng.lat();
                        document.getElementById('frmSite-longitude-0').value=event.latLng.lng();

                    });

                    /*Ajout du marqueur au centre de la map*/
                    addMarker(Ursulette);
                }

                /*Ajout du marqueur dans l'array*/
                function addMarker(location) {
                    var marker = new google.maps.Marker({
                        position: location,
                        map: map
                    });
                    markers.push(marker);
                }

                /*Mise de tous les marqueurs dans l'array*/
                function setMapOnAll(map) {
                    for (var i = 0; i < markers.length; i++) {
                        markers[i].setMap(map);
                    }
                }
        </script>
        <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDxz9dHENw-b-1TlNXw88v3rWtKqCEb2HM&callback=initMap'></script>
        ";
    }

    public function close(){

    }
}
