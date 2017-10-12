<?php

return array(
  '#namespace' => 'controllers',
  '#uses' => array (
  'ControllerBase' => 'controllers\\ControllerBase',
),
  '#traitMethodOverrides' => array (
  'controllers\\ExempleController' => 
  array (
  ),
),
  'controllers\\ExempleController::index' => array(
    array('#name' => 'route', '#type' => 'micro\\annotations\\router\\RouteAnnotation', "/test")
  ),
);

