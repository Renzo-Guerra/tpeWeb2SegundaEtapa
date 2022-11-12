<?php
  require_once './app/models/ModeloPropiedades.php';
  require_once './app/models/ModeloPropietario.php';
  require_once './app/views/ApiView.php';
  require_once './app/Helper.php';

  class ControladorPropietariosApi{
    private $modeloPropietarios;
    private $modeloPropiedades;
    private $view;
    private $data;
    private $helper;

    public function __construct() {
      $this->modeloPropiedades = new ModeloPropiedades();
      $this->modeloPropietarios = new ModeloPropietario();
      $this->view = new ApiView();
      $this->helper = new Helper();
      
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
      $orden = null;      // Indica el orden (ASC o DESC)
      $atributo = null;   // Induca la columna de la tabla (tipo, permite_mascotas, banios, etc)
      $valor = null;      // En caso de traer solo los elementos donde el campo es igual a x, x seria el valor.

      if(isset($_GET["orden"])) $orden = $_GET["orden"];          
      if(isset($_GET["atributo"])) $atributo = $_GET["atributo"]; 
      if(isset($_GET["valor"])) $valor = $_GET["valor"];          

      // Validaciones
      $this->helper->validarOrden($orden);          
      $this->helper->validarColumna($atributo);
      $this->helper->combinacionDeParametrosValidos($orden, $atributo, $valor);
      
      // Casos exitosos
      if(($atributo != null) && ($valor != null))
        // Este seria la busqueda 
        $propiedades = $this->modeloPropiedades->getPropiedadesDonde($atributo, $modeloPropietarios);
      else{
        $propiedades = $this->modeloPropiedades->getPropiedades($orden, $modeloPropietarios);
      }

      $this->view->response($propiedades, 200);die();
    }

    public function getPropietario($params = null) {
      // obtengo el id del arreglo de params
      $dni = $params[':DNI'];
      $propietario = $this->modeloPropiedades->getPropiedades($dni);

      // si no existe devuelvo 404
      if ($propietario)
        $this->view->response($propietario);
      else 
        $this->view->response("El propietario con el dni '${dni}' no existe", 404);
    }

    /**
     * Dado un id, elimina una propiedad.
     * En caso de eliminacion exitosa, el elemnto es retornado.
     */
    public function eliminarPropiedad($params = null) {
      $id = $params[':ID'];
      $propiedadEliminada = $this->modeloPropiedades->modeloPropietarios($id);

      if ($propiedadEliminada) {
        $this->view->response($propiedadEliminada, 200);
      } else 
        $this->view->response("La tarea con el id '{$id}' no existe", 404);
    }

    public function agregarPropiedad($params = null) {
      $propiedad = $this->getData();
      
      // Validaciones
      if($this->modeloPropiedades->camposInvalidos($propiedad)){$this->view->response("Complete los datos", 400); return; die();};
      if($this->modeloPropiedades->inputsInvalidos($propiedad)){$this->view->response("Dato inesperado", 400);return; die();};
      if(!$thismodeloPropietariosuserModel->existeUsuario($propiedad->propietario)){$this->view->response("No se pudo agregar la propiedad porque no existe el usuario '{$$propiedad->propietario}'"); return; die();}
      
      $this->modeloPropiedades->agregarPropiedad($propiedad);
      $this->viewmodeloPropietariosresponse("Nueva propiedad agregada", 201);
    }

  }
