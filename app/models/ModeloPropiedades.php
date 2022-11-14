<?php
  require_once './app/models/Modelo.php';

  class ModeloPropiedades extends Modelo{
    public function __construct($tabla = "tb_propiedad") {
      parent::__construct($tabla);
    }

    /**
     *  Devuelve todas las propiedades ordenadas ascendente o descendentemente por 
     *  una columna de la tabla especificada (En caso de no pasar $atributo, por default toma "precio").
     */
    public function getElementosOrdenados($orden, $atributo="precio") {
      $query = $this->getDb()->prepare("SELECT * FROM tb_propiedad ORDER BY $atributo $orden");
      $query->execute();
      $propiedades = $query->fetchAll(PDO::FETCH_OBJ); 
        
      return $propiedades;
    }

    /**
     * Contaria como el obligatorio 4 (Si solo se le pasa valor), 
     * y como el optativo 8 (ademas de pasarle un valor, traer por un campo especifico).
     */
    function getElementosDonde($valor, $atributo = "id"){
      try { 
        $query = $this->getDb()->prepare("SELECT * FROM tb_propiedad WHERE $atributo = ?");
        $query->execute([$valor]);
        $propiedades = $query->fetchAll(PDO::FETCH_OBJ); 

        return $propiedades;
      } catch (\Throwable $th) {
        return null;
      }
      
    }

    public function agregar($propiedad) {
      try {
        $query = $this->getDb()->prepare("INSERT INTO tb_propiedad (`titulo`, `tipo`, `operacion`, `descripcion`, `precio`, `metros_cuadrados`, `ambientes`, `banios`, `permite_mascotas`, `propietario`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $query->execute([$propiedad->titulo, $propiedad->tipo, $propiedad->operacion, $propiedad->descripcion, $propiedad->precio, $propiedad->metros_cuadrados, $propiedad->ambientes, $propiedad->banios, $propiedad->permite_mascotas, $propiedad->propietario]);
        $id = $this->getDb()->lastInsertId();
        // Devuelve un objeto de la ultima propiedad agregada
        return $this->getElementosDonde($id)[0];   
      } catch (\Throwable $th) {
        // Ocurrio un problema al agragar los datos...
        return null;
      }
    }

    public function eliminar($id) {
      try {
        $propiedadEliminada = $this->getElementosDonde($id);
        $query = $this->getDb()->prepare("DELETE FROM tb_propiedad WHERE id = ?");
        $query->execute([$id]);

        return $propiedadEliminada;
      } catch (\Throwable $th) {
        return null;
      }
    }

    public function editar($propiedad){
      try {
        $query = $this->getDb()->prepare("UPDATE tb_propiedad SET `titulo` = ?,`tipo` = ?,`operacion` = ?,`descripcion`= ?,`precio` = ?,`metros_cuadrados` = ?,`ambientes` = ?,`banios` = ?,`permite_mascotas` = ?,`propietario` = ?  WHERE `id` = ?");
        $query->execute([$propiedad->titulo, $propiedad->tipo, $propiedad->operacion, $propiedad->descripcion, $propiedad->precio, $propiedad->metros_cuadrados, $propiedad->ambientes, $propiedad->banios, $propiedad->permite_mascotas, $propiedad->propietario, $propiedad->id]);
        // Devuelve la propiedad editada
        return $propiedad;   
      } catch (\Throwable $th) {
        return null;
      }
    }

    public function inputsInvalidos($propiedad){
      // Validando inputs de tipo select y radio  
      if(($propiedad->tipo != 'casa') && ($propiedad->tipo != 'departamento') && ($propiedad->tipo != 'ph') && ($propiedad->tipo != 'fondo de comercio') && ($propiedad->tipo != 'terreno baldio')){ return true;}
      if(($propiedad->operacion != 'alquiler') && ($propiedad->operacion != 'venta')){ return true;}
      if(($propiedad->precio < 0) || ($propiedad->metros_cuadrados <= 0) || ($propiedad->ambientes < 0) || ($propiedad->banios < 0) || (($propiedad->permite_mascotas != 1) && ($propiedad->permite_mascotas != 0))){return true;}
      
      return false;
    }

    public function camposInvalidos($propiedad){
      // Validaciones
      if((!isset($propiedad->titulo)) || (!isset($propiedad->tipo)) || (!isset($propiedad->operacion)) || (!isset($propiedad->descripcion)) || (!isset($propiedad->precio)) || (!isset($propiedad->metros_cuadrados)) || (!isset($propiedad->ambientes)) || (!isset($propiedad->banios)) || (!isset($propiedad->permite_mascotas)) || (!isset($propiedad->propietario))){ return true;}
      if(is_null($propiedad->titulo) || is_null($propiedad->tipo) || is_null($propiedad->operacion) || is_null($propiedad->descripcion) || is_null($propiedad->precio) || is_null($propiedad->metros_cuadrados) || is_null($propiedad->ambientes) || is_null($propiedad->banios) || is_null($propiedad->permite_mascotas) || is_null($propiedad->propietario)){ return true;}
      if(empty($propiedad->titulo) || empty($propiedad->tipo) || empty($propiedad->operacion) || empty($propiedad->descripcion) || empty($propiedad->precio) || empty($propiedad->metros_cuadrados) || empty($propiedad->ambientes) || empty($propiedad->propietario)){ return true;}
      
      return false;
    }

  }
