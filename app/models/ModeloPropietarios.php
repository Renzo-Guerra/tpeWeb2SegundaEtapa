<?php
  class ModeloPropietarios{
    private $db;

    public function __construct(){
      $this->db = new PDO('mysql:host=localhost;'.'dbname=db_tpweb;charset=utf8', 'root', '');
    }
    
    /**
     *  Devuelve todos los propietarios ordenados ascendente o descendentemente por 
     *  una columna de la tabla especificada (En caso de no pasar $atributo, por default toma 'apellido').
     */
    public function getPropietariosOrdenados($orden, $atributo = 'apellido') {
      $query = $this->db->prepare("SELECT * FROM tb_propietario ORDER BY $atributo $orden");
      $query->execute();
      $propiedades = $query->fetchAll(PDO::FETCH_OBJ); 
        
      return $propiedades;
    }

        /**
     * Contaria como el obligatorio 4 (Si solo se le pasa valor), 
     * y como el optativo 8 (ademas de pasarle un valor, traer por un campo especifico).
     */
    function getPropietariosDonde($valor, $atributo = "dni"){
      $query = $this->db->prepare("SELECT * FROM tb_propietario WHERE $atributo = ?");
      $query->execute([$valor]);
      $propietarios = $query->fetchAll(PDO::FETCH_OBJ); 
      
      return $propietarios;
    }

    public function getPropietarios(){     
      $query = $this->db->prepare("SELECT * FROM tb_propietario");
      $query->execute();
      $propietarios = $query->fetchAll(PDO::FETCH_OBJ); 
        
      return $propietarios;
    }

    // Determina si un propietario ya existe o no en la tabla (en base a su dni)
    function existePropietario($user_dni){
      $propietario = $this->getPropietariosDonde($user_dni);
      
      // Si ya existe un usuario con ese DNI retorna true, caso contrario retorna false
      return (empty($propietario))? false : true;
    }

    function agregarPropietario($propietario){
      try {
        $query = $this->db->prepare("INSERT INTO tb_propietario (`dni`, `nombre`, `apellido`, `telefono`, `mail`) VALUES (?, ?, ?, ?, ?)");
        $query->execute([$propietario->dni, $propietario->nombre, $propietario->apellido, $propietario->telefono, $propietario->mail]);
        $dni = $this->db->lastInsertId();
        // Devuelve un objeto de la ultima propiedad agregada
        return $this->getPropietariosDonde($propietario->dni)[0];  
      } catch (\Throwable $th) {
        return null;
      }
    }

    function eliminar($user_dni){
      try {
        $propietarioEliminado = $this->getPropietariosDonde($user_dni);
        $query = $this->db->prepare("DELETE FROM tb_propietario WHERE `dni` = ?");
        $query->execute([$user_dni]);  
        
        return $propietarioEliminado;
      } catch (\Throwable $th) {
        return null;
      }
    }

    function editUser($propietario){
      $query = $this->db->prepare("UPDATE tb_propietario SET `nombre` = ?,`apellido` = ?,`telefono` = ?,`mail`= ?  WHERE `dni` = ?");
      $query->execute([$propietario->nombre, $propietario->apellido, $propietario->telefono, $propietario->mail, $propietario->dni]);
    }
    

    public function inputsInvalidos($propietario){
      if(!isset($propietario->dni) || !isset($propietario->nombre) || !isset($propietario->apellido) || !isset($propietario->telefono) || !isset($propietario->mail)){ return true;}
      if(is_null($propietario->dni) || is_null($propietario->nombre) || is_null($propietario->apellido) || is_null($propietario->telefono) || is_null($propietario->mail)){ return true;}
      if(empty($propietario->dni) || empty($propietario->nombre) || empty($propietario->apellido) || empty($propietario->telefono) || empty($propietario->mail)){ return true;}
    
      return false;
    }

    public function camposInvalidos($propietario){
      if($propietario->dni <= 0){return true;}

      return false;
    }

    /**
     * Metodo que dada una columna, verifica si existe en la tabla
     */
    public function existeColumnaEnTabla($columna = null){
      try {
        $query = $this->db->prepare("SHOW COLUMNS FROM tb_propietario LIKE ?");
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