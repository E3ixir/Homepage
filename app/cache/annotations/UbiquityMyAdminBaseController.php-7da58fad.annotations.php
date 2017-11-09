<?php

return array(
  '#namespace' => 'micro\\controllers\\admin',
  '#uses' => array (
  'JString' => 'Ajax\\service\\JString',
  'HtmlHeader' => 'Ajax\\semantic\\html\\elements\\HtmlHeader',
  'HtmlButton' => 'Ajax\\semantic\\html\\elements\\HtmlButton',
  'DAO' => 'micro\\orm\\DAO',
  'OrmUtils' => 'micro\\orm\\OrmUtils',
  'Reflexion' => 'micro\\orm\\parser\\Reflexion',
  'Startup' => 'micro\\controllers\\Startup',
  'Autoloader' => 'micro\\controllers\\Autoloader',
  'UbiquityMyAdminData' => 'micro\\controllers\\admin\\UbiquityMyAdminData',
  'ControllerBase' => 'controllers\\ControllerBase',
  'RequestUtils' => 'micro\\utils\\RequestUtils',
  'HtmlItem' => 'Ajax\\semantic\\html\\content\\view\\HtmlItem',
  'CacheManager' => 'micro\\cache\\CacheManager',
  'Route' => 'micro\\controllers\\admin\\popo\\Route',
  'Router' => 'micro\\controllers\\Router',
  'StrUtils' => 'micro\\utils\\StrUtils',
  'CacheFile' => 'micro\\controllers\\admin\\popo\\CacheFile',
  'HtmlFormFields' => 'Ajax\\semantic\\html\\collections\\form\\HtmlFormFields',
  'ControllerAction' => 'micro\\controllers\\admin\\popo\\ControllerAction',
  'HtmlForm' => 'Ajax\\semantic\\html\\collections\\form\\HtmlForm',
),
  '#traitMethodOverrides' => array (
  'micro\\controllers\\admin\\UbiquityMyAdminBaseController' => 
  array (
  ),
),
  'micro\\controllers\\admin\\UbiquityMyAdminBaseController::$adminData' => array(
    array('#name' => 'var', '#type' => 'mindplay\\annotations\\standard\\VarAnnotation', 'type' => 'UbiquityMyAdminData')
  ),
  'micro\\controllers\\admin\\UbiquityMyAdminBaseController::$adminViewer' => array(
    array('#name' => 'var', '#type' => 'mindplay\\annotations\\standard\\VarAnnotation', 'type' => 'UbiquityMyAdminViewer')
  ),
  'micro\\controllers\\admin\\UbiquityMyAdminBaseController::$adminFiles' => array(
    array('#name' => 'var', '#type' => 'mindplay\\annotations\\standard\\VarAnnotation', 'type' => 'UbiquityMyAdminFiles')
  ),
  'micro\\controllers\\admin\\UbiquityMyAdminBaseController::_getAdminData' => array(
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'UbiquityMyAdminData')
  ),
  'micro\\controllers\\admin\\UbiquityMyAdminBaseController::_getAdminViewer' => array(
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'UbiquityMyAdminViewer')
  ),
  'micro\\controllers\\admin\\UbiquityMyAdminBaseController::_getAdminFiles' => array(
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'UbiquityMyAdminFiles')
  ),
);

