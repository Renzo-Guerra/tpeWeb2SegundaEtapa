<?php
  require_once './libs/Router.php';
  require_once './app/controllers/ControladorPropiedadesApi.php';
  require_once './app/controllers/ControladorPropietariosApi.php';
  
  // crea el router
  $router = new Router();

  // Rutas con propiedades
  $router->addRoute('propiedades', 'GET', 'ControladorPropiedadesApi', 'getElementos');
  $router->addRoute('propiedades/:ID', 'GET', 'ControladorPropiedadesApi', 'getElemento');
  $router->addRoute('propiedades/:ID', 'DELETE', 'ControladorPropiedadesApi', 'eliminarElemento');
  $router->addRoute('propiedades', 'POST', 'ControladorPropiedadesApi', 'agregarElemento'); 
  $router->addRoute('propiedades', 'PUT', 'ControladorPropiedadesApi', 'editarElemento'); 

// Rutas con propietarios
  $router->addRoute('propietarios', 'GET', 'ControladorPropietariosApi', 'getElementos');
  $router->addRoute('propietarios/:DNI', 'GET', 'ControladorPropietariosApi', 'getElemento');
  $router->addRoute('propietarios/:DNI', 'DELETE', 'ControladorPropietariosApi', 'eliminarElemento');
  $router->addRoute('propietarios', 'POST', 'ControladorPropietariosApi', 'agregarElemento'); 
  $router->addRoute('propietarios', 'PUT', 'ControladorPropietariosApi', 'editarElemento'); 
  
  // ejecuta la ruta (sea cual sea)
  $router->route($_GET["resourse"], $_SERVER['REQUEST_METHOD']);
