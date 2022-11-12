<?php
  class Helper{
    
    // Valida que el orden pasado (en caso de no ser null) sea los aceptados por mi documentacion
    public function validarOrden($orden){
      if(($orden != null) && (($orden != "ASC") && ($orden != "DESC"))){
        $this->view->response("Error, 'orden' invalido", 400); die(); 
      }
    }

    // Valida que el atributo (columna) pasada (en caso de no ser null) esté en la tabla 'tb_propiedades'.
    public function validarColumna($columna){
      if(($columna != null) && (!($this->modeloPropiedad->existeColumnaEnTabla($columna)))){
        $this->view->response("Error, 'atributo' invalido", 400); die(); 
      }
    }

    public function combinacionDeParametrosValidos($orden, $atributo, $valor){
      // Podria haber hecho esas 3 validaciones en 1 sola, pero queria que cada una tenga un mensaje "unico"
      if(($orden != null) && ($atributo != null) && ($valor != null)){
        $this->view->response("Error, no se puede obtener informacion por la combinacion 'orden', 'atributo' y 'valor'.", 400); die();
      }
      if(($orden != null) && ($valor != null)){
        $this->view->response("Error, no se puede obtener informacion por la combinacion 'orden' y 'valor'.", 400); die();
      }
      if(($orden == null) && ($atributo == null) && ($valor != null)){
        //* Se podria en si, pero para eso está el endpoint getPropiedad/:ID || getPropietario/:DNI
        $this->view->response("Error, no se puede obtener informacion solo brindando 'valor'.", 400); die();
      }
    }
  }