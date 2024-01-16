<?php
session_start();
require_once 'modelos/ConexionDB.php';
require_once 'modelos/AnunciosDAO.php';
require_once 'modelos/Anuncio.php';
require_once 'funciones.php';

$conexionDB = new ConexionDB("id21583343_marcos", "12345Aa_","localhost", "id21583343_tienda");
$conn = $conexionDB->getConexion();

$idAnuncio = htmlentities($_GET['id']);
$anunciosDAO = new AnunciosDAO($conn);

$anuncio = $anunciosDAO->getById($idAnuncio);

$anuncioMod = new Anuncio();
if($_SERVER['REQUEST_METHOD'] == "POST"){
    if (isset($_FILES['archivos'])) {
        $archivos = $_FILES['archivos'];
        $numArchivos = count($archivos['name']);

        for ($i = 0; $i < $numArchivos; $i++) {
            $nombreArchivo = $archivos['name'][$i];
            $tipoArchivo = $archivos['type'][$i];
            $errorArchivo = $archivos['error'][$i];
        
            //Comprobamos que la imagen tiene un formato adecuado y en caso de tenerlo procedemos a guardar la imagen
            if($tipoArchivo != 'image/jpeg' && $tipoArchivo != 'image/gif'){
                $error = "La imagen debe ser jpeg o gif";
            }else{
                //Llamamos a este metodo pra generar un nombre unico para evitar conflictos con otras imagenes que tengan un nombre paracido
                $foto = generarNombre($nombreArchivo);

                //Y aqui en caso de encontrar en la carpeta fotosAnuncios una foto con un nombre igual ejecutara el metodo generar nombre hasta encontrar uno unico
                while(file_exists("fotosAnuncios/$foto")){
                    $foto = generarNombre($_nombreArchivo);
                }

                if(!move_uploaded_file($_FILES['archivos']['tmp_name'][$i], "fotosAnuncios/$foto")){
                    die("Error al copiar la foto a la carpeta fotosAnuncios");
                }
            }
            
            if($i != ($numArchivos-1)){
                $fotos = $fotos . $foto. ";";
            }else{
                $fotos = $fotos . $foto;
            }
        }

        $anuncioMod->setFoto($fotos);
    }

    $anuncioMod->setId($anuncio->getId());
    $anuncioMod->setTitulo(htmlentities($_POST['titulo']));
    $anuncioMod->setDescripcion(htmlspecialchars_decode($_POST['descripcion']));
    $anuncioMod->setPrecio(htmlentities($_POST['precio']));
    $anuncioMod->setFechaPublicacion(htmlentities($_POST['fecha']));
    $anuncioMod->setIdUsuario($_SESSION['id']);
    
    if($anunciosDAO->modificarAnuncio($anuncioMod)){
        header('location: index.php');
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="js/jquery-te-1.4.0.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="js/jquery-te-1.4.0.min.js"></script>
    <style>
        body{
            background-image: url('fondo.jpg');
            background-position: center;
        }

        h1{
            text-align: center;
            font-size: 40px;
            margin-top: 0;
            margin-bottom: 3%;
            border-bottom: solid black 2px;
            padding:5%;
        }

        .formulario{
            font-size:25px;
            margin-top:4.5%;
            margin-left: 35%;
            margin-right: 35%;
            background-color: white;
            border: solid black 2px;
            border-radius: 10px;
            width: 30%;
        }

        .formulario label{
            margin: auto;
            padding:4%;
            padding-left: 6%;
            padding-right: 0;
            width: 100px;
        }

        .formulario input{
            margin-bottom:10px;
        }

        .jqte {
            margin: 10px;
            margin-left: 18px;
            margin-right: 18px;
        }

        #boton{
            height: 40px;
            margin-left: 36%;
            width: 30%;
            margin-top:15px;
            margin-bottom:15px;
         }
    </style>
</head>
<body>
<?php mostrarError() ?>
    <div class="formulario">
        <h1>Modificar Anuncio</h1>
        <form action="modificarAnuncio.php?id=<?= $idAnuncio?>" method="post" enctype="multipart/form-data">
            <label for="titulo">Titulo:</label>
            <input type="text" name="titulo" placeholder="titulo" value="<?= $anuncio->getTitulo()?>" required><br>
            <label for="descripcion">Descripcion:</label>
            <textarea name="descripcion" class="jqte_editor" placeholder="descripcion"><?= $anuncio->getDescripcion()?></textarea>
            <label for="precio">Precio:</label>
            <input type="text" name="precio" placeholder="precio" value="<?= $anuncio->getPrecio()?>" required><br>
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" placeholder="YYYY-MM-DD" value="<?= $anuncio->getFechaPublicacion()?>"><br>
            <label for="foto">Fotos:</label>
            <input type="file" name="archivos[]" placeholder="foto" accept="image/jpeg, image/gif" multiple required><br>
            <input type="submit" value="Modificar Anuncio" id="boton">
        </form>
    </div>
    <p style="text-align:center">Pulse aqui para <a href="index.php">volver</a></p>
    <script>
        $(document).ready(function(){
            $('.jqte_editor').jqte();
        });
    </script>
</body>
</html>