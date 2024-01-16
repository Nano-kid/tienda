<?php
class UsuariosDAO{
    private mysqli $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function cargarUsuarios(){
        if(!$stmt = $this->conn->prepare('SELECT * FROM Usuarios')){
            echo "Error en la SQL" . $stmt->conn->error;
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $usuarios = array();
        while($usuario = $result->fetch_object(Usuario::class)){
            $usuarios[] = $usuario;
        }
        return $usuarios;

    }

    public function insertarUsuario($usuario){
        if(!$stmt = $this->conn->prepare("INSERT INTO Usuarios (email, password, nombre, telefono, poblacion, sid) VALUES (?, ?, ?, ?, ?, ?)")){
            echo "Error en la SQL " . $stmt->conn->error;
        }
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();
        $nombre = $usuario->getNombre();
        $telefono = $usuario->getTelefono();
        $poblacion = $usuario->getPoblacion();
        $sid = $usuario->getSid();
        $stmt->bind_param("ssssss", $email, $password, $nombre, $telefono, $poblacion, $sid);
        if($stmt->execute()){
            return $stmt->insert_id;
        }else{
            return false;
        }
    }

    public function getByEmail($email){
        if(!$stmt = $this->conn->prepare("SELECT * FROM Usuarios WHERE email = ?")){
            echo "Error al ejecuta la SQL " . $this->conn->error;
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_object(Usuario::class);
        return $usuario;
    }

    public function getBySid($sid){
        if(!$stmt = $this->conn->prepare("SELECT * FROM Usuarios WHERE sid = ?")){
            echo "Error al ejecuta la SQL " . $this->conn->error;
        }
        $stmt->bind_param("s", $sid);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_object(Usuario::class);
        return $usuario;
    }


}
?>