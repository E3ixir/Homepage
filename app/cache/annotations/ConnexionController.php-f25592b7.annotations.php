<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'ControllerBase' => 'controllers\\ControllerBase',
),
  '#traitMethodOverrides' => array (
  'controllers\\ConnexionController' => 
  array (
  ),
),
  'controllers\\ConnexionController::index' => array(
    array('#name' => 'route', '#type' => 'micro\\annotations\\router\\RouteAnnotation', "/connexion_utilisateur")
  ),
);

