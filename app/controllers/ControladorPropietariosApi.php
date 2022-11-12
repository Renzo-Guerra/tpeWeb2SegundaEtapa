<?php
  require_once './app/models/ModeloPropiedades.php';
  require_once './app/models/ModeloPropietario.php';
  require_once './app/views/ApiView.php';
  require_once './app/Helper.php';

  class ControladorPropiedadesApi{
    private $modeloPropietario;
    private $view;
    private $data;
    private $helper;

    public function __construct() {
      $this->propertyModel = new ModeloPropiedades();
      $this->userModel = new ModeloPropietario();
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
      $this->validarOrden($orden);          
      $this->validarColumna($atributo);
      // Podria haber hecho esas 3 validaciones en 1 sola, pero queria que cada una tenga un mensaje "unico"
      if(($orden != null) && ($atributo != null) && ($valor != null)){
        $this->view->response("Error, no se puede obtener informacion por la combinacion 'orden', 'atributo' y 'valor'.", 400); die();
      }
      if(($orden != null) && ($valor != null)){
        $this->view->response("Error, no se puede obtener informacion por la combinacion 'orden' y 'valor'.", 400); die();
      }
      if(($orden == null) && ($atributo == null) && ($valor != null)){
        $this->view->response("Error, no se puede obtener informacion solo brindando 'valor'.", 400); die();
      }
      
      // Casos exitosos
      if(($atributo != null) && ($valor != null))
        // Este seria la busqueda 
        $propiedades = $this->propertyModel->getPropiedadesWhere($atributo, $valor);
      else{
        $propiedades = $this->propertyModel->getPropiedades($orden, $atributo);
      }

      $this->view->response($propiedades, 200);die();
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
