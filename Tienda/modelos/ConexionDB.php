<?php
class ConexionDB{

    private $conn;

    function __construct($user, $password, $host, $database){
        $this->conn = new mysqli($host, $user, $password, $database);
        if($this->conn->connect_error){
            die("Error al conectar con la base de datos");
        }
    }

    public function getConexion(){
        return $this->conn;
    }
}
?>