<?php
  require_once './app/controllers/Controlador.php';
  require_once './app/models/ModeloPropietarios.php';

  class ControladorPropietariosApi extends Controlador{
    public function __construct($modelo = new ModeloPropietarios()) {
      parent::__construct($modelo);
    }
    
    public function getAsociativo($params = null){
      // obtengo el id del arreglo de params
      return $params[':DNI'];
    }

    public function validarParaAgregar($propietario){
      $this->validarCampos($propietario);
      if($this->modelo->existe($propietario->dni)){$this->vista->response("Error: Ya existe el elemento con el dni '{$propietario->dni}'"); die();}
    }
    public function validarParaEditar($propietario){
      $this->validarCampos($propietario);
      if(!$this->modelo->existe($propietario->dni)){$this->vista->response("El propietario con el dni '{$propietario->dni}' no existe", 404); die();}
    }
  }
