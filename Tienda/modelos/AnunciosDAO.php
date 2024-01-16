<?php
class AnunciosDAO{
    private mysqli $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function cargarAnuncios($idUser, $limit, $offset):array{
        if($limit == null){
            if(!$stmt = $this->conn->prepare("SELECT * FROM Anuncios ORDER BY fechaPublicacion ASC")){
                echo "Error en la SQL: " . $stmt->conn->error;
            }
        }elseif($idUser != null){
            if(!$stmt = $this->conn->prepare("SELECT * FROM Anuncios WHERE idUsuario = ? ORDER BY fechaPublicacion ASC LIMIT ? OFFSET ?")){
                echo "Error en la SQL: " . $stmt->conn->error;
            }
        $stmt->bind_param("iii", $idUser, $limit, $offset);
        }else{
            if(!$stmt = $this->conn->prepare("SELECT * FROM Anuncios ORDER BY fechaPublicacion ASC LIMIT ? OFFSET ?")){
                echo "Error en la SQL: " . $stmt->conn->error;
            }
        $stmt->bind_param("ii", $limit, $offset);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $anuncios = array();
        //Para poder hacer referencia a esa clase anuncio hemos necesitado ñadir un require en el archivo en el que se llama a este metodo
        while($anuncio = $result->fetch_object(Anuncio::class)){
            $anuncios[] = $anuncio;
        }
        return $anuncios;
    }

    public function insertarAnuncios($anuncio){
        //Se crea el statement del insert pra poder insertar en anuncio
        if(!$stmt = $this->conn->prepare("INSERT INTO Anuncios(titulo, descripcion, precio, fechaPublicacion, foto, idUsuario) VALUES (?,?,?,?,?,?)")){
            echo "Error en la SQL: " . $stmt->conn->error;
        }
            //Se guardan en variables los diferentes atributos del anuncio para a traves del bind_param pasarselo a los values del statement    
            $titulo = $anuncio->getTitulo();
            $descripcion = $anuncio->getDescripcion();
            $precio = $anuncio->getPrecio();
            $fechaPublicacion = $anuncio->getFechaPublicacion();          
            $foto = $anuncio->getFoto();
            $idUsuario = $anuncio->getIdUsuario();
            $stmt->bind_param('ssdssi', $titulo, $descripcion, $precio, $fechaPublicacion, $foto, $idUsuario);
            if($stmt->execute()){
                return $stmt->insert_id;
            }else{
                return false;
            }
    }

    public function modificarAnuncio($anuncio){
        if(!$stmt = $this->conn->prepare("UPDATE Anuncios SET titulo = ?, descripcion = ?, precio = ?, fechaPublicacion = ?, foto = ?, idUsuario = ? WHERE id = ?")){
            echo "Error en la SQL: " . $this->conn->error;
        }
        $id = $anuncio->getId();
        $titulo = $anuncio->getTitulo();
        $descripcion = $anuncio->getDescripcion();
        $precio = $anuncio->getPrecio();
        $fechaPublicacion = $anuncio->getFechaPublicacion();
        $foto = $anuncio->getFoto();
        $idUsuario = $anuncio->getIdUsuario();
        $stmt->bind_param('ssdssii', $titulo, $descripcion, $precio, $fechaPublicacion, $foto, $idUsuario, $id);
        return $stmt->execute();
    }

    public function borrarAnuncios($id){
        if(!$stmt = $this->conn->prepare("DELETE FROM Anuncios WHERE id = ?")){
            echo "Error al ejecutar la SQL: " . $this->conn->error;
        }
        $stmt->bind_param("s", $id);
        $stmt->execute();
        if($stmt->affected_rows==1){
            return true;
        }else{
            return false;
        }
    }

    public function getById($idAnuncio){
        if(!$stmt = $this->conn->prepare("SELECT * FROM Anuncios WHERE id = ?")){
            echo "Error en al ejecutar al SQL: " . $this->conn->error;
        }
        $stmt->bind_param("s", $idAnuncio);
        $stmt->execute();
        $result = $stmt->get_result();
        $anuncio = $result->fetch_object(Anuncio::class);
        return $anuncio;
    }
}
?>