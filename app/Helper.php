<?php
  class Helper{
    
    // Valida que el orden pasado (en caso de no ser null) sea los aceptados por mi documentacion
    public function validarOrden($orden){
      if(($orden != null) && (($orden != "ASC") && ($orden != "DESC"))){
        $this->view->response("Error, 'orden' invalido", 400); die(); 
      }
    }

    public function combinacionDeParametrosValidos($orden, $atributo, $valor){
      // Podria haber hecho esas 3 validaciones en 1 sola, pero queria que cada una tenga un mensaje "unico"
      if( (($orden != null) && ($atributo != null) && ($valor != null)) || 
          (($orden != null) && ($valor != null)) || 
          (($orden == null) && ($atributo == null) && ($valor != null)) ){ // Se supone que para eso esta el endpoint con /:ID || /:DNI
          return false;
      }
      
      return true;
    }
  }