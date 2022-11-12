<?php
  class ModeloPropietario{
    private $db;

    public function __construct(){
      $this->db = new PDO('mysql:host=localhost;'.'dbname=db_tpweb;charset=utf8', 'root', '');
    }
    
    /**
     *  Devuelve todos los propietarios ordenados ascendente o descendentemente por 
     *  una columna de la tabla especificada (En caso de no pasar $atributo, por default toma 'apellido').
     */
    public function getPropiedadesOrdenadas($atributo = 'apellido', $orden) {
      if($orden = "ASC")
        $query = $this->db->prepare("SELECT * FROM tb_propiedad ORDER BY $atributo ASC");
      else
        $query = $this->db->prepare("SELECT * FROM tb_propiedad ORDER BY $atributo DESC");

      $query->execute();
      $propiedades = $query->fetchAll(PDO::FETCH_OBJ); 
        
      return $propiedades;
    }

    // Determines weather a user already has that specific dni
    function existeUsuario($user_dni){
      $query = $this->db->prepare("SELECT * FROM tb_propietario WHERE `dni` = ?");
      $query->execute([$user_dni]);
      
      $user = $query->fetch(PDO::FETCH_OBJ);
      
      // Si ya existe un usuario con ese DNI retorna true, caso contrario retorna false
      return (empty($user))? false : true;
    }

    function agregarUsuario($dni, $name, $surname, $phone, $mail){
      // Verificamos que no exista un usuario con ese dni
      $exist = $this->existeUsuario($dni);
      if($exist){ return false;}

      $query = $this->db->prepare("INSERT INTO tb_propietario (`dni`, `nombre`, `apellido`, `telefono`, `mail`) VALUES (?, ?, ?, ?, ?)");
      $query->execute([$dni, $name, $surname, $phone, $mail]);
      if($this->existeUsuario($dni))
        return getUserById($dni); // Devolvemos el nuevo objeto añadido
      else
        return false; // Hubo un error al agregar el usuario
    }

    function getAllUsers(){
      $query = $this->db->prepare("SELECT * FROM tb_propietario");
      $query->execute();
      $users = $query->fetchAll(PDO::FETCH_OBJ);

      return $users;
    }

    function deleteUser($user_dni){
      // Validation
      if(!$this->existeUsuario($user_dni)){ return;}

      $query = $this->db->prepare("DELETE FROM tb_propietario WHERE `dni` = ?");
      $query->execute([$user_dni]);
    }

    function getUserById($user_dni){
      $query = $this->db->prepare("SELECT * FROM tb_propietario WHERE `dni` = ?");
      $query->execute([$user_dni]);
      $user_data = $query->fetch(PDO::FETCH_OBJ);

      return $user_data;
    }

    function editUser($dni, $nombre, $apellido, $telefono, $mail){
      $query = $this->db->prepare("UPDATE tb_propietario SET `nombre` = ?,`apellido` = ?,`telefono` = ?,`mail`= ?  WHERE `dni` = ?");
      $query->execute([$nombre, $apellido, $telefono, $mail, $dni]);
    }
    
    public function camposInvalidosPropietarios($propietario){
      // Validaciones
      if(is_null($_GET['dni']) || is_null($_GET['name']) || is_null($_GET['surname']) || is_null($_GET['phone']) || is_null($_GET['mail'])){ return true;}
      if(empty($_GET['dni']) || empty($_GET['name']) || empty($_GET['surname']) || empty($_GET['phone']) || empty($_GET['mail'])){ return true;}
      
      return false;
    }
  }