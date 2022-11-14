<?php
  require_once './app/views/ApiView.php';

  class Helper{
    private $db;
    private $vista;

    public function __construct(){
      $this->db = new PDO('mysql:host=localhost;'.'dbname=db_tpweb;charset=utf8', 'root', '');
      $this->vista = new ApiView();
    }
    
    // Valida que el orden pasado (en caso de no ser null) sea los aceptados por mi documentacion
    public function validarOrden($orden){
      if(($orden != null) && (($orden != "ASC") && ($orden != "DESC"))){
        $this->vista->response("Error, 'orden' invalido", 400); die(); 
      }
    }

    public function combinacionDeParametrosValidos($orden, $atributo, $valor){
      if( (($orden != null) && ($atributo != null) && ($valor != null)) || 
          (($orden != null) && ($valor != null)) || 
          (($orden == null) && ($atributo == null) && ($valor != null)) ){ // Se supone que para eso esta el endpoint con /:ID || /:DNI
            $this->vista->response("Error, no se sabe que hacer con esa peticion (combinacion de 'claves' no declarada).", 400); die();
      }
    }

    public function getData(){
      // lee el body del request y lo transforma de json a objeto.
      return json_decode(file_get_contents("php://input"));
    }

    // Valida que el atributo (columna) pasada (en caso de no ser null) esté en la tabla 'tb_propietarioes'.
    public function validarColumna($tabla, $columna){
      if(($columna != null) && (!($this->existeColumnaEnTabla($tabla, $columna)))){
        $this->vista->response("Error, 'atributo' invalido", 400); die(); 
      }
    }

    /**
     * Metodo que dada una columna, verifica si existe en la tabla
     */
    private function existeColumnaEnTabla($tabla, $columna){
      try {
        $consulta = "SHOW COLUMNS FROM " . $tabla . " LIKE ?";
        $query = $this->db->prepare($consulta);
        $query->execute([$columna]);
        $resultado = $query->fetchAll(PDO::FETCH_OBJ);  
        
        return $resultado;
      } catch (\Throwable $th) {
        $this->vista->response("Hubo un error al validar la columna", 403);
        return false;
      }
    }
  }