<?php
  require_once './libs/Router.php';
  require_once './app/controllers/ControladorPropiedadesApi.php';

  // crea el router
  $router = new Router();

  // Rutas con propiedades
  $router->addRoute('propiedades', 'GET', 'ControladorPropiedadesApi', 'getPropiedades');
  $router->addRoute('propiedades/:ID', 'GET', 'ControladorPropiedadesApi', 'getPropiedad');
  $router->addRoute('propiedades/:ID', 'DELETE', 'ControladorPropiedadesApi', 'eliminarPropiedad');
  $router->addRoute('propiedades', 'POST', 'ControladorPropiedadesApi', 'agregarPropiedad'); 

  // ejecuta la ruta (sea cual sea)
  $router->route($_GET["resourse"], $_SERVER['REQUEST_METHOD']);
