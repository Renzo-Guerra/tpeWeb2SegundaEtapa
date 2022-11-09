<?php
  class ModeloUsuario{
    private $db;

    public function __construct(){
      $this->db = new PDO('mysql:host=localhost;'.'dbname=db_tpweb;charset=utf8', 'root', '');
    }
    
    // Determines weather a user already has that specific dni
    function existeUsuario($user_dni){
      $query = $this->db->prepare("SELECT * FROM `tb_propietario` WHERE `dni` = ?");
      $query->execute([$user_dni]);
      
      $user = $query->fetch(PDO::FETCH_OBJ);
      
      // Si ya existe un usuario con ese DNI retorna true, caso contrario retorna false
      return (empty($user))? false : true;
    }

    function agregarUsuario($dni, $name, $surname, $phone, $mail){
      // Verificamos que no exista un usuario con ese dni
      $exist = $this->existeUsuario($dni);
      if($exist){ return false;}

      $query = $this->db->prepare("INSERT INTO `tb_propietario` (`dni`, `nombre`, `apellido`, `telefono`, `mail`) VALUES (?, ?, ?, ?, ?)");
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
  }