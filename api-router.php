<?php
  require_once './libs/Router.php';
  require_once './app/controllers/ControladorPropiedadesApi.php';
  require_once './app/controllers/ControladorPropietariosApi.php';
  
  // crea el router
  $router = new Router();

  // Rutas con propiedades
  $router->addRoute('propiedades', 'GET', 'ControladorPropiedadesApi', 'getPropiedades');
  $router->addRoute('propiedades/:ID', 'GET', 'ControladorPropiedadesApi', 'getPropiedad');
  $router->addRoute('propiedades/:ID', 'DELETE', 'ControladorPropiedadesApi', 'eliminarPropiedad');
  $router->addRoute('propiedades', 'POST', 'ControladorPropiedadesApi', 'agregarPropiedad'); 

// Rutas con propietarios
  $router->addRoute('propietarios', 'GET', 'ControladorPropietariosApi', 'getPropietarios');
  // ejecuta la ruta (sea cual sea)
  $router->route($_GET["resourse"], $_SERVER['REQUEST_METHOD']);
