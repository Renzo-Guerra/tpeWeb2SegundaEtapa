<?php
  require_once './app/models/ModeloPropiedades.php';
  require_once './app/models/ModeloUsuario.php';
  require_once './app/views/ApiView.php';

  class ControladorPropiedadesApi{
    private $propertyModel;
    private $userModel;
    private $view;
    private $data;

    public function __construct() {
      $this->propertyModel = new ModeloPropiedades();
      $this->userModel = new ModeloUsuario();
      $this->view = new ApiView();
      
      // lee el body del request
      $this->data = file_get_contents("php://input");
    }

    private function getData() {
      return json_decode($this->data);
    }

    /**
     * - Requerimiento funcional optativo numero 9.
     * - (A su vez es el requerimiento funcional obligatorio numero 3).
     * - Si solo se le pasa el orden, los ordenará en base al "precio" de la propiedad.
     */
    public function getPropiedades() {
      $orden = null;
      $atributo = null;

      if(isset($_GET["orden"]))
        $orden = $_GET["orden"];
      
      if(isset($_GET["atributo"]))
        $atributo = $_GET["atributo"];

      $propiedades = $this->propertyModel->getPropiedades($orden, $atributo);
      if($propiedades != null)
        $this->view->response($propiedades, 200);
      else
        $this->view->response("Error, 'orden' o 'atributo' invalido", 400);
    }
    
    public function getPropiedad($params = null) {
      // obtengo el id del arreglo de params
      $id = $params[':ID'];
      $propiedad = $this->propertyModel->getPropiedad($id);

      // si no existe devuelvo 404
      if ($propiedad)
        $this->view->response($propiedad);
      else 
        $this->view->response("La tarea con el id '${id}' no existe", 404);
    }

    /**
     * Dado un id, elimina una propiedad.
     * En caso de eliminacion exitosa, el elemnto es retornado.
     */
    public function eliminarPropiedad($params = null) {
      $id = $params[':ID'];
      $propiedadEliminada = $this->propertyModel->eliminar($id);

      if ($propiedadEliminada) {
        $this->view->response($propiedadEliminada, 200);
      } else 
        $this->view->response("La tarea con el id '{$id}' no existe", 404);
    }

    public function agregarPropiedad($params = null) {
      $propiedad = $this->getData();
      
      // Validaciones
      if($this->propertyModel->camposInvalidos($propiedad)){$this->view->response("Complete los datos", 400); return; die();};
      if($this->propertyModel->inputsInvalidos($propiedad)){$this->view->response("Dato inesperado", 400);return; die();};
      if(!$this->userModel->existeUsuario($propiedad->propietario)){$this->view->response("No se pudo agregar la propiedad porque no existe el usuario '{$$propiedad->propietario}'"); return; die();}
      
      $this->propertyModel->agregarPropiedad($propiedad);
      $this->view->response("Nueva propiedad agregada", 201);
    }

  }
