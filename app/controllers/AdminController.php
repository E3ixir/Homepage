<?php

namespace controllers;
use Ajax\JsUtils;
use micro\orm\DAO;
use micro\utils\RequestUtils;
use models;
use models\Site;

/**
 * Controller AdminController
 * @property JsUtils $jquery
 **/

class AdminController extends ControllerBase{

    public function index(){

        $semantic=$this->jquery->semantic();
        $bts=$semantic->htmlButtonGroups("bts",["Liste des sites","Ajout d'un site"]);
        $bts->setPropertyValues("data-ajax", ["all/","addSite/"]);
        $bts->getOnClick("AdminController/","#divSites",["attr"=>"data-ajax"]);
        $this->jquery->compile($this->view);
        $this->loadView("Admin\index.html");
        //$this->loadView("sites\index.html",["jsMap"=>$this->_generateMap(49.201491, -0.380734)]);
    }

    private function tout(){

        $sites=DAO::getAll("models\Site");
        $semantic=$this->jquery->semantic();
        $table=$semantic->dataTable("tblSites", "models\Site", $sites);
        $table->setIdentifierFunction(function($i,$obj){return $obj->getId();});
        $table->setFields(["id","nom","latitude","longitude","ecart","fondEcran","couleur","ordre","options"]);
        $table->setCaptions(["Id","Nom","Latitude","Longitude","Ecart","Fond d'écran","Couleur", "Ordre", "Options", "Actions"]);
        $table->addEditDeleteButtons(true,["ajaxTransition"=>"random","method"=>"post"]);
        $table->setUrls(["","AdminController/edit","AdminController/delete"]);
        $table->setTargetSelector("#divSites");

        echo $table->compile($this->jquery);
        echo $this->jquery->compile();
    }

    public function all() {

        $this->tout();
        $this->jquery->compile($this->view);
        $this->loadView("Admin\index.html");
    }

    public function addSite(){
        $this->_form(new Site(),"AdminController/newSite/",49.201491,-0.380734);
    }

    private function _form($site, $action,$lat,$long){

        $semantic=$this->jquery->semantic();
        $semantic->setLanguage("fr");
        $form=$semantic->dataForm("frmSite", $site);
        $form->setValidationParams(["on"=>"blur", "inline"=>true]);
        $form->setFields(["nom\n","latitude","longitude","ecart\n","fondEcran\n","couleur\n","ordre\n","options\n","submit"]);
        $form->setCaptions(["Nom","Latitude","Longitude","Ecart","Fond d'écran","Couleur", "Ordre", "Options","Valider"]);
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

    public function newSite(){

        $site=new Site();
        RequestUtils::setValuesToObject($site,$_POST);
        if(DAO::insert($site)){
            echo "Le site ".$site->getNom()." a bien été ajouté.";
        }
    }

    public function delete($id){
        //  if(RequestUtils::isPost())
        //{
        //echo " - ".$id." - ";
        $site=DAO::getOne("models\Site", $id);
        $site instanceof models\Site && DAO::remove($site);
        $this->forward("controllers\AdminController","all");
        //echo "le site {$site} a bien été supprimé";
        /*if($site instanceof models\Site && DAO::remove($site))
         {
         echo "le site {$site} a bien été supprimé";
         }else{ echo "impossible a supp";}*/
        //}
        //else{echo "Vous n'êtes pas autorisé à vous rendre ici.";}
    }

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

    public function edit($id){
        //if($site=$this->_getSiteInGet()){
        $site=DAO::getOne("models\Site", $id);
        $this->_form($site,"AdminController/update/".$id,$site->getLatitude(),$site->getLongitude());
        //$site instanceof models\Site && DAO::update($site);
        //$this->jquery->postFormOnClick("#btValider","AdminController/update", "frmEdit","#divSites");
        //$this->jquery->compile($this->view);

        //        $this->loadView("AdminController/edit.html");
        //}else{echo 'Vous n'êtes pas autorisé à vous rendre ici.';}
    }

    public function update($id){
        $site=DAO::getOne("models\Site", $id);
        RequestUtils::setValuesToObject($site,$_POST);
        if(DAO::update($site)){
            echo "Le site ".$site->getNom()." a été modifié.";
        }
    }

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
                        document.getElementById('lat').value=event.latLng.lat();
                        document.getElementById('lng').value=event.latLng.lng();

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
}
