<?php
  require_once './app/models/ModeloPropietarios.php';
  require_once './app/views/ApiView.php';
  require_once './app/Helper.php';

  class ControladorPropietariosApi{
    private $modeloPropietarios;
    private $view;
    private $data;
    private $helper;

    public function __construct() {
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
    public function getPropietarios() {
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
        $propiedades = $this->modeloPropietarios->getPropietarios();
      }else if(($atributo != null) && ($valor != null)){
        // Este seria la busqueda del opcional 8 
        $propiedades = $this->modeloPropietarios->getPropietariosDonde($valor, $atributo);
      }else if(($atributo != null) && ($orden != null)){
        // Este seria la busqueda del opcional 9 
        $propiedades = $this->modeloPropietarios->getPropietariosOrdenados($orden, $atributo);
      }else if($orden != null){
        // Este seria la busqueda del obligatorio 3
        $propiedades = $this->modeloPropietarios->getPropietariosOrdenados($orden);
      }

      $this->view->response($propiedades, 200);die();
    }

    // Valida que el atributo (columna) pasada (en caso de no ser null) esté en la tabla 'tb_propietarioes'.
    public function validarColumna($columna){
      if(($columna != null) && (!($this->modeloPropietarios->existeColumnaEnTabla($columna)))){
        $this->view->response("Error, 'atributo' invalido", 400); die(); 
      }
    }

    public function getPropietario($params = null) {
      // obtengo el id del arreglo de params
      $dni = $params[':DNI'];
      $propietario = $this->modeloPropietarios->getPropietariosDonde($dni);

      // si no existe devuelvo 404
      if ($propietario)
        $this->view->response($propietario[0]);
      else 
        $this->view->response("El propietario con el dni '${dni}' no existe", 404);
    }

    /**
     * Dado un id, elimina un propietario (Y todas sus propiedades (ya que la relacion estaba en CASCADA)).
     * En caso de eliminacion exitosa, el elemento es retornado.
     */
    public function eliminarPropietario($params = null) {
      $dni = $params[':DNI'];

      // Se verifica que exista un propietario con ese dni
      if(!$this->modeloPropietarios->existePropietario($dni))
        $this->view->response("El propietario con el dni '{$dni}' no existe", 404); die();
      
      $propietarioEliminado = $this->modeloPropietarios->eliminar($dni);
      // Se verifica si $propietarioEliminado tiene o no el ultimo eliminado
      if (is_null($propietarioEliminado))
        $this->view->response("Peticion abortada por posible inyeccion", 403);  
      else
        $this->view->response($propietarioEliminado, 200);
    }

    public function agregarPropietario($params = null) {
      $propietario = $this->getData();
      $this->validarTodo($propietario);
      
      $agregado = $this->modeloPropietarios->agregarPropietario($propietario);
      if(is_null($agregado))
        $this->view->response("Peticion abortada por posible inyeccion", 403);  
      else
        $this->view->response("Nueva propietario agregado", 201);
    }

    public function validarTodo($propietario){
      // Validaciones
      if($this->modeloPropietarios->camposInvalidos($propietario)){$this->view->response("Complete los datos", 400); die();};
      if($this->modeloPropietarios->inputsInvalidos($propietario)){$this->view->response("Dato inesperado", 400); die();};
    }

    public function editarPropietario(){
      $propietario = $this->getData();
      $this->validarTodo($propietario);
      
      $existe = $this->modeloPropietarios->existePropietario($propietario->dni);
      if(!$existe){$this->view->response("No existe ningun propietario con el dni '{$propietario->dni}'.", 404); die();}
      
      $propietarioEditado = $this->modeloPropietarios->editar($propietario);
      if(is_null($propietarioEditado))
        $this->view->response("Peticion abortada, no se pudo editar la propiedad", 403);
      else
        $this->view->response($propietarioEditado, 200);
    }

  }
