<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'JsUtils' => 'Ajax\\JsUtils',
  'DAO' => 'micro\\orm\\DAO',
  'Site' => 'models\\Site',
  'Utilisateur' => 'models\\Utilisateur',
  'RequestUtils' => 'micro\\utils\\RequestUtils',
  'models' => 'models',
  'HtmlFormInput' => 'Ajax\\semantic\\html\\collections\\form\\HtmlFormInput',
  'FormLogin' => 'Ajax\\semantic\\widgets\\business\\user\\FormLogin',
),
  '#traitMethodOverrides' => array (
  'controllers\\SiteController' => 
  array (
  ),
),
  'controllers\\SiteController' => array(
    array('#name' => 'property', '#type' => 'mindplay\\annotations\\standard\\PropertyAnnotation', 'type' => 'JsUtils', 'name' => 'jquery')
  ),
  'controllers\\SiteController::index' => array(
    array('#name' => 'route', '#type' => 'micro\\annotations\\router\\RouteAnnotation', "/all")
  ),
  'controllers\\SiteController::all' => array(
    array('#name' => 'route', '#type' => 'micro\\annotations\\router\\RouteAnnotation', "/all","cache"=>true,"duration"=>15)
  ),
  'controllers\\SiteController::menu' => array(
    array('#name' => 'route', '#type' => 'micro\\annotations\\router\\RouteAnnotation', "menu/")
  ),
  'controllers\\SiteController::printLien' => array(
    array('#name' => 'route', '#type' => 'micro\\annotations\\router\\RouteAnnotation', "/liensweb")
  ),
  'controllers\\SiteController::disconnected' => array(
    array('#name' => 'route', '#type' => 'micro\\annotations\\router\\RouteAnnotation', "/disconnected")
  ),
  'controllers\\SiteController::userConnected' => array(
    array('#name' => 'route', '#type' => 'micro\\annotations\\router\\RouteAnnotation', "/userConnected")
  ),
);

