<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'DAO' => 'micro\\orm\\DAO',
  'RequestUtils' => 'micro\\utils\\RequestUtils',
  'Utilisateur' => 'models\\Utilisateur',
  'HtmlFormInput' => 'Ajax\\semantic\\html\\collections\\form\\HtmlFormInput',
),
  '#traitMethodOverrides' => array (
  'controllers\\ConnexionController' => 
  array (
  ),
),
  'controllers\\ConnexionController::index' => array(
    array('#name' => 'route', '#type' => 'micro\\annotations\\router\\RouteAnnotation', "/connexion_utilisateur")
  ),
  'controllers\\ConnexionController::userRegister' => array(
    array('#name' => 'route', '#type' => 'micro\\annotations\\router\\RouteAnnotation', "/userRegister")
  ),
  'controllers\\ConnexionController::userConnection' => array(
    array('#name' => 'route', '#type' => 'micro\\annotations\\router\\RouteAnnotation', "/userConnection")
  ),
);

