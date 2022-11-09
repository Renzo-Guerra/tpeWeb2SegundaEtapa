<?php
  class ModeloPropiedades {
    private $db;

    public function __construct() {
      $this->db = new PDO('mysql:host=localhost;'.'dbname=db_tpweb;charset=utf8', 'root', '');
    }

    /**
     * Devuelve todas las propiedades ordenadas por precio 
     * (Ascendente o descendentemente).
     * - En caso de que el orden sea diferente a "ASC" o "DESC" devuelve null.
     */
    private function getPropiedadesSortPrecio($orden = null) {
      if($orden == "ASC"){
        $query = $this->db->prepare("SELECT * FROM tb_propiedad ORDER BY `precio` ASC");
      }else if($orden == "DESC"){
        $query = $this->db->prepare("SELECT * FROM tb_propiedad ORDER BY `precio` DESC");
      }else{
        return null;
      }

      $query->execute();
      $propiedades = $query->fetchAll(PDO::FETCH_OBJ); 
        
      return $propiedades;
    }
    
    /**
     * Devuelve todas las propiedades.
     * - Si se le pasa un "orden", lo devuelve por default ordenado por el atributo "precio".
     * - Si a su vez se le pasa un "atributo", el orden es en base al "atributo" pasado.
     * - En caso de que alguno de los campos pasados sea erroneo (O se mande solo el atributo), devolverá null.
     */
    public function getPropiedades($orden = null, $atributo = null) {
      // Caso en que no se pase ni orden ni atributo
      if(($orden == null) && ($atributo == null)){
        $query = $this->db->prepare("SELECT * FROM tb_propiedad");
        $query->execute();
        $propiedades = $query->fetchAll(PDO::FETCH_OBJ); 
        
        return $propiedades;
      }
      // Caso en que solo se pase el orden
      if($atributo == null){
        // Reutilizo el metodo de sort por default 
        // (Tranquilamente se podria eliminar el metodo e implementar) el codigo del metodo en este if...
        $propiedades = $this->getPropiedadesSortPrecio($orden);
        return $propiedades;
      }else{
        // Caso en que el criterio de ordenamiento no exista en la tabla o que $orden sea != de "ASC" y de "DESC"
        if((!$this->existeColumnaEnTabla($atributo)) || (($orden != "ASC") && ($orden != "DESC"))){
          return null;
        }else{
          // Caso en que el criterio de ordenamiento exista en la tabla y tambien se haya pasado un orden
          /**
           * No se usa el $query->execute([$atributo, $precio]) porq PDO no permite hacerlo asi, 
           * pero es seguro porq ya se valido que $atributo exista en la tabla, y que $orden sea "ASC" o "DESC", 
           * no pueden hacer inyeccion.
           */
          $query = $this->db->prepare("SELECT * FROM tb_propiedad ORDER BY $atributo $orden");
          $query->execute();
          $propiedades = $query->fetchAll(PDO::FETCH_OBJ); 
          
          return $propiedades;
        }
      }
    }

    public function getPropiedad($id) {
      $query = $this->db->prepare("SELECT * FROM tb_propiedad WHERE `id` = ?");
      $query->execute([$id]);
      $property = $query->fetch(PDO::FETCH_OBJ);

      return $property;
    }

    public function agregarPropiedad($propiedad) {
      $query = $this->db->prepare("INSERT INTO `tb_propiedad`(`titulo`, `tipo`, `operacion`, `descripcion`, `precio`, `metros_cuadrados`, `ambientes`, `banios`, `permite_mascotas`, `propietario`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $query->execute([$propiedad->titulo, $propiedad->tipo, $propiedad->operacion, $propiedad->descripcion, $propiedad->precio, $propiedad->metros_cuadrados, $propiedad->ambientes, $propiedad->banios, $propiedad->permite_mascotas, $propiedad->propietario]);
    }

    function eliminar($id) {
      $propiedadEliminada = $this->getPropiedad($id);
      $query = $this->db->prepare('DELETE FROM tb_propiedad WHERE id = ?');
      $query->execute([$id]);

      return $propiedadEliminada;
    }

    public function camposInvalidos($propiedad){
      // Validaciones
      if(is_null($propiedad->titulo) || is_null($propiedad->tipo) || is_null($propiedad->operacion) || is_null($propiedad->descripcion) || is_null($propiedad->precio) || is_null($propiedad->metros_cuadrados) || is_null($propiedad->ambientes) || is_null($propiedad->banios) || is_null($propiedad->permite_mascotas) || is_null($propiedad->propietario)){ return true;}
      if(empty($propiedad->titulo) || empty($propiedad->tipo) || empty($propiedad->operacion) || empty($propiedad->descripcion) || empty($propiedad->precio) || empty($propiedad->metros_cuadrados) || empty($propiedad->ambientes) || empty($propiedad->propietario)){ return true;}

      return false;
    }

    public function inputsInvalidos($propiedad){
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
        $query = $this->db->prepare("SHOW COLUMNS FROM `tb_propiedad` LIKE ?");
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
