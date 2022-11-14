<?php
  class Helper{
    private $vista;

    public function __construct(){
      $vista = new Vista();
    }
    
    // Valida que el orden pasado (en caso de no ser null) sea los aceptados por mi documentacion
    public function validarOrden($orden){
      if(($orden != null) && (($orden != "ASC") && ($orden != "DESC"))){
        $this->view->response("Error, 'orden' invalido", 400); die(); 
      }
    }

    public function combinacionDeParametrosValidos($orden, $atributo, $valor){
      if( (($orden != null) && ($atributo != null) && ($valor != null)) || 
          (($orden != null) && ($valor != null)) || 
          (($orden == null) && ($atributo == null) && ($valor != null)) ){ // Se supone que para eso esta el endpoint con /:ID || /:DNI
            $this->view->response("Error, no se sabe que hacer con esa peticion (combinacion de parametros no declarada).", 400); die();
      }
    }

    public function getData(){
      // lee el body del request y lo transforma de json a objeto.
      return json_decode(file_get_contents("php://input"));
    }
  }