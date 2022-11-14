<?php
  require_once './app/views/ApiView.php';
  require_once './app/Helper.php';

  abstract class Controlador{
    protected $modelo;
    protected $vista;
    protected $helper;
    
    public function __construct($modelo){
      $this->modelo = $modelo;
      $this->vista = new ApiView();
      $this->helper = new Helper();
    }

    /**
     * - Requerimiento funcional optativo numero 9.
     * - (A su vez es el requerimiento funcional obligatorio numero 3).
     * - Si solo se le pasa el orden, los ordenará en base al:
     *    * "precio" (en caso de una propiedad)
     *    * "apellido" (en caso de un propietario)
     */
    public function getElementos() {
      $orden = null;      // Indica el orden (ASC o DESC)
      $atributo = null;   // Induca la columna de la tabla
      $valor = null;      // En caso de traer solo los elementos donde el campo es igual a x, x seria el valor.
      
      if(isset($_GET["orden"])) $orden = $_GET["orden"];          
      if(isset($_GET["atributo"])) $atributo = $_GET["atributo"]; 
      if(isset($_GET["valor"])) $valor = $_GET["valor"];          
      
      // Validaciones
      $this->helper->validarOrden($orden);       
      $this->helper->validarColumna($this->modelo->getTabla(), $atributo);
      $this->helper->combinacionDeParametrosValidos($orden, $atributo, $valor);

      
      // Casos exitosos
      if(($atributo == null) && ($valor == null) && ($orden == null)){
        // Caso "vanilla"
        $elementos = $this->modelo->getElementos();
      }else if(($atributo != null) && ($valor != null)){
        // Este seria la busqueda del opcional 8 
        $elementos = $this->modelo->getElementosDonde($valor, $atributo);
      }else if(($atributo != null) && ($orden != null)){
        // Este seria la busqueda del opcional 9 
        $elementos = $this->modelo->getElementosOrdenados($orden, $atributo);
      }else if($orden != null){
        // Este seria la busqueda del obligatorio 3
        // Los ordena por precio (propiedades) y por apellidos (propietarios)
        $elementos = $this->modelo->getElementosOrdenados($orden);
      }

      $this->vista->response($elementos, 200);die();
    }

    public function agregarElemento($params = null) {
      $elemento = $this->helper->getData();
      $this->validarParaAgregar($elemento);
      $nuevoElemento = $this->modelo->agregar($elemento);
      
      (is_null($nuevoElemento))
        ?$this->vista->response("Peticion abortada, hubo un error al agregar el elemento", 403)
        :$this->vista->response($nuevoElemento, 201);
    }

    public function getElemento($params = null) {
      $pk = $this->getAsociativo($params);
      $elemento = $this->modelo->getElementosDonde($pk);
      
      ($elemento) 
        ? $this->vista->response($elemento[0], 200)  
        : $this->vista->response("El elemento no existe", 404); 
    }

    /**
     * - Dado un parametro (el de la Primary Key), 
     * elimina un elemento de la tabla correspondiente.
     * - Si se elimina un propietario, por consecuencia se eliminaran TODAS sus propiedades.
     * - En caso de eliminacion exitosa, el elemento es retornado.
     */
    public function eliminarElemento($params = null) {
      $pk = $this->getAsociativo($params);
      // Se verifica que exista una propiedad con ese id
      
      if(empty($this->modelo->existe($pk))){
        $this->vista->response("El elemento no existe", 404); die();
      }

      $elementoEliminado = $this->modelo->eliminar($pk);
      (is_null($elementoEliminado))
        ?$this->vista->response("Peticion abortada, hubo un error al eliminar el elemento", 403)
        :$this->vista->response($elementoEliminado[0], 200); die();
    }

    function editarElemento(){
      $elemento = $this->helper->getData();
      $this->validarParaEditar($elemento);
      
      $elementoEditado = $this->modelo->editar($elemento);
      (is_null($elementoEditado))
        ?$this->vista->response("Peticion abortada, no se pudo editar el elemento", 403)
        :$this->vista->response($elementoEditado, 200);
    }

    public function validarCampos($elemento){
      if($this->modelo->camposInvalidos($elemento)){$this->vista->response("Complete los datos", 400); die();};
      if($this->modelo->inputsInvalidos($elemento)){$this->vista->response("Dato inesperado", 400); die();};
    }

    public abstract function validarParaAgregar($elemento);
    public abstract function validarParaEditar($elemento);
    public abstract function getAsociativo($params = null);
  }