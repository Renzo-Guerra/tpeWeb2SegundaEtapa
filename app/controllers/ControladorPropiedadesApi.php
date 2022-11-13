<?php
  require_once './app/models/ModeloPropiedades.php';
  require_once './app/models/ModeloPropietarios.php';
  require_once './app/views/ApiView.php';
  require_once './app/Helper.php';

  class ControladorPropiedadesApi{
    private $modeloPropiedades;
    private $modeloPropietarios;
    private $view;
    private $data;
    private $helper;

    public function __construct() {
      $this->modeloPropiedades = new ModeloPropiedades();
      $this->modeloPropietarios = new ModeloPropietarios();
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
      $this->validarColumna($atributo);

      $esValido = $this->helper->combinacionDeParametrosValidos($orden, $atributo, $valor);
      if(!$esValido){
        $this->view->response("Error, no se sabe que hacer con esa peticion (combinacion de parametros no declarada).", 400); die();
      }
      // Casos exitosos
      if(($atributo == null) && ($valor == null) && ($orden == null)){
        // Caso "vanilla"
        $propiedades = $this->modeloPropiedades->getPropiedades();
      }else if(($atributo != null) && ($valor != null)){
        // Este seria la busqueda del opcional 8 
        $propiedades = $this->modeloPropiedades->getPropiedadesDonde($valor, $atributo);
      }else if(($atributo != null) && ($orden != null)){
        // Este seria la busqueda del opcional 9 
        $propiedades = $this->modeloPropiedades->getPropiedadesOrdenadas($orden, $atributo);
      }else if($orden != null){
        // Este seria la busqueda del obligatorio 3
        $propiedades = $this->modeloPropiedades->getPropiedadesOrdenadas($orden);
      }

      $this->view->response($propiedades, 200);die();
    }

    // Valida que el atributo (columna) pasada (en caso de no ser null) esté en la tabla 'tb_propietarioes'.
    public function validarColumna($columna){
      if(($columna != null) && (!($this->modeloPropiedades->existeColumnaEnTabla($columna)))){
        $this->view->response("Error, 'atributo' invalido", 400); die(); 
      }
    }

    public function getPropiedad($params = null) {
      // obtengo el id del arreglo de params
      $id = $params[':ID'];
      $propiedad = $this->modeloPropiedades->getPropiedadesDonde($id);

      // si no existe devuelvo 404
      if ($propiedad)
        $this->view->response($propiedad[0]);
      else 
        $this->view->response("La tarea con el id '${id}' no existe", 404);
    }

    /**
     * Dado un id, elimina una propiedad.
     * En caso de eliminacion exitosa, el elemnto es retornado.
     */
    public function eliminarPropiedad($params = null) {
      $id = $params[':ID'];

      // Se verifica que exista una propiedad con ese id
      if(!$this->modeloPropiedades->existePropietario($id))
        $this->view->response("La propiedad con el id '{$id}' no existe", 404); die();
      
      $propiedadEliminada = $this->modeloPropiedades->eliminar($id);
      // Se verifica si $propiedadEliminada tiene o no el ultimo eliminado
      if (is_null($propiedadEliminada))
        $this->view->response("Peticion abortada, hubo un error al eliminar la propiedad", 403);  
      else
        $this->view->response($propiedadEliminada, 200);
    }

    public function validarTodo($propiedad){
      // Validaciones
      if($this->modeloPropiedades->camposInvalidos($propiedad)){$this->view->response("Complete los datos", 400); die();};
      if($this->modeloPropiedades->inputsInvalidos($propiedad)){$this->view->response("Dato inesperado", 400); die();};
    }

    public function agregarPropiedad($params = null) {
      $propiedad = $this->getData();
      $this->validarTodo($propiedad);
      if(!$this->modeloPropietarios->existePropietario($propiedad->propietario)){$this->view->response("No se pudo agregar la propiedad porque no existe el usuario '{$propiedad->propietario}'"); die();}

      $nuevaPropiedad = $this->modeloPropiedades->agregarPropiedad($propiedad);
      if(is_null($nuevaPropiedad))
        $this->view->response("Peticion abortada, hubo un error al agregar la propiedad", 403);  
      else
        $this->view->response($nuevaPropiedad, 201);
    }

    function editarPropiedad(){
      $propiedad = $this->getData();
      $this->validarTodo($propiedad);
      $existe = $this->modeloPropiedades->existePropiedad($propiedad->id);
      if(!$existe){$this->view->response("La propiedad con el id '{$propiedad->id}' no existe", 404); die();}
      
      $existe = $this->modeloPropietarios->existePropietario($propiedad->propietario);
      if(!$existe){$this->view->response("No existe ningun propietario con el id '{$propiedad->id}'.", 404); die();}
      
      $propiedadEditada = $this->modeloPropiedades->editar($propiedad);
      if(is_null($propiedadEditada))
        $this->view->response("Peticion abortada, no se pudo editar la propiedad", 403);
      else
        $this->view->response($propiedadEditada, 200);
    }

  }
