<?php
  require_once './app/controllers/Controlador.php';
  require_once './app/models/ModeloPropiedades.php';
  require_once './app/models/ModeloPropietarios.php';


  class ControladorPropiedadesApi extends Controlador{
    private $modeloPropietarios;

    public function __construct($modelo = new ModeloPropiedades()) {
      parent::__construct($modelo);
      $this->modeloPropietarios = new ModeloPropietarios();
    }
    public function getAsociativo($params = null){
      // obtengo el id del arreglo de params
      return $params[':ID'];
    }

    public function validarParaAgregar($propiedad){
      $this->validarCampos($propiedad);
      if(!$this->modeloPropietarios->existe($propiedad->propietario)){$this->vista->response("Error: No existe el usuario '{$propiedad->propietario}'"); die();}
    }

    public function validarParaEditar($propiedad){
      $this->validarCampos($propiedad);
      if(!$this->modelo->existe($propiedad->id)){$this->vista->response("La propiedad con el id '{$propiedad->id}' no existe", 404); die();}
    }
  }
