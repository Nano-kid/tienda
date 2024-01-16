<?php 
require_once 'modelos/ConexionDB.php';
require_once 'modelos/AnunciosDAO.php';
require_once 'modelos/Anuncio.php';

$conexionDB = new ConexionDB("id21583343_marcos", "12345Aa_","localhost", "id21583343_tienda");
$conn = $conexionDB->getConexion();

$idAnuncio = $_GET['id'];
$anunciosDAO = new AnunciosDAO($conn);
$anuncio = $anunciosDAO->getById($idAnuncio);
//Gestinar error de que sea obligatorio por lo menos seleccionar una foto
$arrayFotos = explode(";", $anuncio->getFoto());

$contador = isset($_POST['contador']) ? $_POST['contador']: 0;
if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_POST['antFoto'])){
        $contador--;
        if($contador < 0){
            $contador = 0;
        }
    }  

    if(isset($_POST['sigFoto'])){
        $contador++;
        if($contador > (count($arrayFotos) -1)){
            $contador = (count($arrayFotos) -1);
        }
    } 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            background-image: url('fondo.jpg');
            background-position: center;
        }

        .contenedor{
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            padding-top: 2%;
        }

        header{
            margin: 0px auto;
            padding:5px;
            background-color: white;
            border:2px solid rgb(233, 166, 0);
            width: 80%;
            position: relative;
            height: 180px;
        }

        .imagenes{
            position: relative;
            padding-left: 20%;
            width:75%;
        }

        #imagen{
            display: block;
            height: 590px;
            width: 100%;
            background-color: white;
            border:2px solid rgb(233, 166, 0);
            border-radius: 5px;
        }

        form{
            display: inline;
        }

        form input{
            margin-top: 15px;
            font-size: 20px;
        }

        .informacion{
            background-color: white;
            border:2px solid rgb(233, 166, 0);
            border-radius: 5px;
        }

        h1{
            text-align: center;
            font-size: 55px;
        }

        h1 a{
            text-decoration: underline;
            color: black;
        }

        h2{
            text-align: center;
            font-size: 40px;
        }
    </style>
</head>
<body>
    <header>
        <h1 style="margin-top: 55px"><a href="index.php">KäN SARE</a></h1>
    </header>
    <div class="contenedor">
        <div class="imagenes">
                <img id="imagen" src="fotosAnuncios/<?=$arrayFotos[$contador]?>">
            <?php if(count($arrayFotos) > 1):?>
                <form action="VerAnuncio.php?id=<?=$idAnuncio?>" method="post" style="padding-left: 25%">
                    <input type="hidden" name="contador" value="<?=$contador?>">
                    <input type="submit" name="antFoto" value="Foto Anterior">
                </form>
                <form action="VerAnuncio.php?id=<?=$idAnuncio?>" method="post" style="margin-left: 5px">
                    <input type="hidden" name="contador" value="<?=$contador?>">
                    <input type="submit" name="sigFoto" value="Siguiente Foto">
                </form>
            <?php endif; ?>
        </div>
        <div style="width:80%">
            <div class="informacion">
                <h1><?=$anuncio->getTitulo()?></h1>
                <p style="font-size: 20px; margin-left: 10px;"><?=$anuncio->getDescripcion()?> </p>
                <h2>Precio: <?=$anuncio->getPrecio()?></h2>
                <p style="text-align:center">Anuncio publicado el: <?=$anuncio->getFechaPublicacion()?></p>
            </div>
        </div>
    </div>
</body>
</html>