<?php
  abstract class Modelo{
    protected $db;
    private $tabla;
    
    public function __construct($tabla){
      $this->db = new PDO('mysql:host=localhost;'.'dbname=db_tpweb;charset=utf8', 'root', '');
      $this->tabla = $tabla;
    }

    // Getters
    public function getTabla(){return $this->tabla;}
    public function getDb(){return $this->db;}

    function existe($pk){
      $elemento = $this->getElementosDonde($pk);
      return (empty($elemento))? false : true;
    }

    public function getElementos(){     
      $consulta = "SELECT * FROM " . $this->getTabla();
      $query = $this->db->prepare($consulta);
      $query->execute();
      $elementos = $query->fetchAll(PDO::FETCH_OBJ); 
        
      return $elementos;
    }
    public abstract function getElementosDonde($valor, $atributo);
    public abstract function agregar($elemento);
    public abstract function eliminar($elemento);
    public abstract function editar($elemento);
    public abstract function inputsInvalidos($elemento);
    public abstract function camposInvalidos($elemento);
  }