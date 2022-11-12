<?php
  class ModeloPropiedades {
    private $db;

    public function __construct() {
      $this->db = new PDO('mysql:host=localhost;'.'dbname=db_tpweb;charset=utf8', 'root', '');
    }

    /**
     *  Devuelve todas las propiedades ordenadas ascendente o descendentemente por 
     *  una columna de la tabla especificada (En caso de no pasar $atributo, por default toma "precio").
     */
    public function getPropiedadesOrdenadas($atributo = 'precio', $orden) {
      if($orden == "ASC")
        $query = $this->db->prepare("SELECT * FROM tb_propiedad ORDER BY $atributo ASC");
      else
        $query = $this->db->prepare("SELECT * FROM tb_propiedad ORDER BY $atributo DESC");

      $query->execute();
      $propiedades = $query->fetchAll(PDO::FETCH_OBJ); 
        
      return $propiedades;
    }

    /**
     * Contaria como el obligatorio 4 (Si solo se le pasa valor), 
     * y como el optativo 8 (ademas de pasarle un valor, traer por un campo especifico).
     */
    function getPropiedadesDonde($valor, $atributo = "id"){
      $query = $this->db->prepare("SELECT * FROM tb_propiedad WHERE $atributo = ?");
      $query->execute([$valor]);
      $propiedades = $query->fetchAll(PDO::FETCH_OBJ); 
      
      return $propiedades;
    }

    public function getPropiedades(){     
      $query = $this->db->prepare("SELECT * FROM tb_propiedad");
      $query->execute();
      $propiedades = $query->fetchAll(PDO::FETCH_OBJ); 
        
      return $propiedades;
    }

    // public function getPropiedades($orden = null, $atributo = null) {
    //   // Caso en que el criterio de ordenamiento no exista en la tabla o que $orden sea != de "ASC" y de "DESC"
    //   if((!$this->existeColumnaEnTabla($atributo)) || (($orden != "ASC") && ($orden != "DESC"))){
    //     return null;
    //   }
    // }

    public function agregarPropiedad($propiedad) {
      $query = $this->db->prepare("INSERT INTO tb_propiedad (`titulo`, `tipo`, `operacion`, `descripcion`, `precio`, `metros_cuadrados`, `ambientes`, `banios`, `permite_mascotas`, `propietario`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $query->execute([$propiedad->titulo, $propiedad->tipo, $propiedad->operacion, $propiedad->descripcion, $propiedad->precio, $propiedad->metros_cuadrados, $propiedad->ambientes, $propiedad->banios, $propiedad->permite_mascotas, $propiedad->propietario]);
    }

    function eliminar($id) {
      $propiedadEliminada = $this->getPropiedad($id);
      $query = $this->db->prepare("DELETE FROM tb_propiedad WHERE id = ?");
      $query->execute([$id]);

      return $propiedadEliminada;
    }

    public function camposInvalidosPropiedad($propiedad){
      // Validaciones
      if(is_null($propiedad->titulo) || is_null($propiedad->tipo) || is_null($propiedad->operacion) || is_null($propiedad->descripcion) || is_null($propiedad->precio) || is_null($propiedad->metros_cuadrados) || is_null($propiedad->ambientes) || is_null($propiedad->banios) || is_null($propiedad->permite_mascotas) || is_null($propiedad->propietario)){ return true;}
      if(empty($propiedad->titulo) || empty($propiedad->tipo) || empty($propiedad->operacion) || empty($propiedad->descripcion) || empty($propiedad->precio) || empty($propiedad->metros_cuadrados) || empty($propiedad->ambientes) || empty($propiedad->propietario)){ return true;}
      if(($propiedad->precio < 0) || ($propiedad->metros_cuadrados <= 0) || ($propiedad->ambientes < 1)){return true;}
      
      return false;
    }

    public function inputsInvalidosPropiedad($propiedad){
      // Validando inputs de tipo select y radio  
      if(($propiedad->tipo != 'casa') && ($propiedad->tipo != 'departamento') && ($propiedad->tipo != 'ph') && ($propiedad->tipo != 'fondo de comercio') && ($propiedad->tipo != 'terreno baldio')){ return true;}
      if(($propiedad->operacion != 'alquiler') && ($propiedad->operacion != 'venta')){ return true;}
      if(($propiedad->permite_mascotas != 1) && ($propiedad->permite_mascotas != 0)){ return true;}

      return false;
    }

    /**
     * Metodo que dada una columna, verifica si existe en la tabla
     */
    public function existeColumnaEnTabla($columna = null){
      try {
        $query = $this->db->prepare("SHOW COLUMNS FROM tb_propiedad LIKE ?");
        $query->execute([$columna]);
        $resultado = $query->fetchAll(PDO::FETCH_OBJ);  
        // $resultado es un array con objetos de todas las columnas coincidientes.
        // print_r($resultado);
      } catch (\Throwable $th) {
        $resultado = null;
      }

      return $resultado;
    }
  }
