<?php
  require_once './app/models/Modelo.php';

  class ModeloPropietarios extends Modelo{
    public function __construct($tabla = "tb_propietario") {
      parent::__construct($tabla);
    }

    /**
     *  Devuelve todos los propietarios ordenadas ascendente o descendentemente por 
     *  una columna de la tabla especificada (En caso de no pasar $atributo, por default toma "apellido").
     */
    public function getElementosOrdenados($orden, $atributo="apellido") {
      $query = $this->getDb()->prepare("SELECT * FROM tb_propietario ORDER BY $atributo $orden");
      $query->execute();
      $propiedades = $query->fetchAll(PDO::FETCH_OBJ); 
        
      return $propiedades;
    }

    /**
     * Contaria como el obligatorio 4 (Si solo se le pasa valor), 
     * y como el optativo 8 (ademas de pasarle un valor, traer por un campo especifico).
     */
    function getElementosDonde($valor, $atributo = "dni"){
      $query = $this->getDb()->prepare("SELECT * FROM tb_propietario WHERE $atributo = ?");
      $query->execute([$valor]);
      $propietarios = $query->fetchAll(PDO::FETCH_OBJ); 
      
      return $propietarios;
    }

    function agregar($propietario){
      try {
        $query = $this->getDb()->prepare("INSERT INTO tb_propietario (`dni`, `nombre`, `apellido`, `telefono`, `mail`) VALUES (?, ?, ?, ?, ?)");
        $query->execute([$propietario->dni, $propietario->nombre, $propietario->apellido, $propietario->telefono, $propietario->mail]);
        $dni = $this->getDb()->lastInsertId();
        // Devuelve un objeto de la ultima propiedad agregada
        return $this->getElementosDonde($propietario->dni)[0];  
      } catch (\Throwable $th) {
        return null;
      }
    }

    function eliminar($user_dni){
      try {
        $propietarioEliminado = $this->getElementosDonde($user_dni);
        $query = $this->getDb()->prepare("DELETE FROM tb_propietario WHERE `dni` = ?");
        $query->execute([$user_dni]);  
        
        return $propietarioEliminado;
      } catch (\Throwable $th) {
        return null;
      }
    }

    function editar($propietario){
      try {
        $query = $this->getDb()->prepare("UPDATE tb_propietario SET `nombre` = ?,`apellido` = ?,`telefono` = ?,`mail`= ?  WHERE `dni` = ?");
        $query->execute([$propietario->nombre, $propietario->apellido, $propietario->telefono, $propietario->mail, $propietario->dni]);
        return $propietario;
      } catch (\Throwable $th) {
        return null;
      }
      
    }
    

    public function inputsInvalidos($propietario){
      if($propietario->dni <= 0){return true;}

      return false;
    }

    public function camposInvalidos($propietario){
      if(!isset($propietario->dni) || !isset($propietario->nombre) || !isset($propietario->apellido) || !isset($propietario->telefono) || !isset($propietario->mail)){ return true;}
      if(is_null($propietario->dni) || is_null($propietario->nombre) || is_null($propietario->apellido) || is_null($propietario->telefono) || is_null($propietario->mail)){ return true;}
      if(empty($propietario->dni) || empty($propietario->nombre) || empty($propietario->apellido) || empty($propietario->telefono) || empty($propietario->mail)){ return true;}
    
      return false;
    }
  }