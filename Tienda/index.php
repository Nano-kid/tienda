<?php
session_start();
require_once 'modelos/ConexionDB.php';
require_once 'modelos/AnunciosDAO.php';
require_once 'modelos/Anuncio.php';
require_once 'modelos/UsuariosDAO.php';
require_once 'modelos/Usuario.php';
require_once 'funciones.php';

$ConexionDB = new ConexionDB("id21583343_marcos", "12345Aa_","localhost", "id21583343_tienda");
$conn = $ConexionDB->getConexion();

$AnunciosDAO = new AnunciosDAO($conn);

if(isset($_COOKIE['sid']) && !isset($_SESSION['email'])){
    $usuariosDAO = new UsuariosDAO($conn);
    if($usuarioSesion = $usuariosDAO->getBySid($_COOKIE['sid'])){
        $_SESSION['email'] = $usuarioSesion->getEmail();
        $_SESSION['id'] = $usuarioSesion->getId();
        $_SESSION['password'] = $usuarioSesion->getPassword();
        setcookie('sid', $usuarioSesion->getSid(), time()+7*24*60*60,"/");
    }
}

$paginaActual = isset($_POST['pagina']) ? $_POST['pagina'] : 0;
if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_SESSION['cargarAnuncios']) && $_SESSION['cargarAnuncios'] == true){
        $numAnuncios = $AnunciosDAO->cargarAnuncios(null, null, null);
    }

    if(isset($_SESSION['cargarPorId']) && $_SESSION['cargarPorId'] == true){
        $numAnuncios = $AnunciosDAO->cargarAnuncios(null, $_SESSION['id'], null);
    }

    $numPaginas = floor(count($numAnuncios)/5);
    if(isset($_POST['antPag'])){
        $paginaActual--;
        if($paginaActual < 0){
            $paginaActual = 0;
        }

        if(isset($offset) && $offset < 0){
            $offset = 0;
        }
    }  
    
    if(isset($_POST['sigPag'])){
        $paginaActual++;
        if($paginaActual > $numPaginas){
            $paginaActual = $numPaginas;
        }
    }
    $offset = ($paginaActual) * 5;
}else{
    $offset = 0;
}

//Mostar Anuncios
if((isset($_GET['cargarAnuncios']) && $_GET['cargarAnuncios'] == true) || (isset($_SESSION['cargarAnuncios']) && $_SESSION['cargarAnuncios'] == true)){
    $idUser = null;
    $anuncios = $AnunciosDAO->cargarAnuncios($idUser, 5 , $offset);
    
    $_SESSION['cargarAnuncios'] = true;
    $_SESSION['cargarPorId'] = false;
    }


//Mostrar mis anuncios
if((isset($_GET['cargarPorId']) && $_GET['cargarPorId'] == true) || (isset($_SESSION['cargarPorId']) && $_SESSION['cargarPorId'] == true)){
    if(isset($_SESSION['id'])){
        $idUser = $_SESSION['id'];
        $anunciosId = $AnunciosDAO->cargarAnuncios($idUser, 5, $offset);
        $_SESSION['cargarPorId'] = true;
        $_SESSION['cargarAnuncios'] = false;
    }else{
        guardarError("Para ver tus anuncios necesitas iniciar sesion");
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

        header{
            margin: 0px auto;
            padding:5px;
            background-color: white;
            border:2px solid rgb(233, 166, 0);
            width: 80%;
            position: relative;
            height: 180px;
        }

        h1{
            position: relative;
            text-align: center;
            margin-top: 55px;
            height: 60%;
            font-size: 60px;
        }

        .menu{
            margin: 0px auto;
            margin-top: 15px;
            padding:5px;
            padding-top:12px;
            position: relative;
            background-color: white;
            border:2px solid rgb(233, 166, 0);
            width: 80%;
            height:34px;
            text-align: center;

        }

        .menu > *{
            font-size:16px;
            margin-left:0;
            margin-right:3%;
        }

        .menu > *:last-child {
            margin-right: 0;
        }

        .menu form{
            display: inline;
        }

        .menu form input{
            margin-left:0;
            margin-right:2%;
            text-decoration: none;
        }

        .error{
        color:red;
        display: block;
        padding: 5px;
        margin: auto;
        width: 80%;
        border: 1px solid red;
        text-align: center;
        margin-top: 20px;
        }

        .contenedor {
            margin-top: 20px;
            margin-bottom: -10px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
        }

        .anuncio:nth-child(even){
            margin-left: 6%;
            grid-column-start: 2;
            grid-column-end: 2;
        }

        .anuncio:nth-child(odd) {
            grid-column-start: 1;
            grid-column-end: 1;
            margin-left: 22%;
        }

        .anuncio {
            text-align: center;
            margin-bottom: 5%;
            width: 70%;
            padding: 10px;

        }

        .anuncio p{
            display: inline;
            text-align: center;
            justify-content: center;
            font-size: 25px;
        }

        .anuncio p a{
            color: inherit;
            text-decoration: none;
        }

        .productos{
            width:90%/*465px*/; 
            height: 500px;
            margin-top: 15px;
            margin: auto;
            text-align: center;
            display: block;
            border: solid rgb(233, 166, 0) 2px;
            border-radius: 5px;
        }

        .titulos{
            background-color: white;
            border: solid rgb(233, 166, 0) 2px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        button{
            margin-top: 20px;
            font-size:20px;
        }

        button > a{
            text-decoration: none;
            color: black;
        }

        .paginacion{
            display: inline;
        }

        .paginacion input{
            margin-top: 15px;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>KäN SARE</h1>
    </header>
    <div class="menu">
        <a href="index.php?cargarAnuncios=true, cargarPorId=false"><?php $paginaActual = 0; $offset = 0?>Anuncios</a>
        <a href="index.php?cargarPorId=true, cargarAnuncios=false"><?php $paginaActual = 0; $offset = 0?>Mis Anuncios</a>
        <?php if(!isset($_SESSION['email'])): ?>
        <form action="login.php" method="post">
            <label for="email">Email:</label>
            <input type="text" name="email" id="email">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
            <input style="font-size: 15px" type="submit" value="Iniciar Sesion">
        </form>
        <a href="insertarUsuario.php">Registrarse</a>
        <?php else:?>
            <span><?= $_SESSION['email'] ?></span>
            <a href="logout.php">Cerrar sesión</a>
        <?php endif;?> 
    </div>
    <?php mostrarError() ?>
    <p><a href="InsertarAnuncio.php" style=" text-align: center; display: block; font-size: 18px;">Añadir Auncio</a></p>
    <?php if(isset($anuncios) && $_SESSION['cargarAnuncios'] == true): ?>
        <div class="contenedor">
            <?php foreach($anuncios as $anuncio): ?>
                <div class="anuncio">
                    <div class="titulos">
                        <p><a href="VerAnuncio.php?id=<?=$anuncio->getId()?>"><strong>Producto:</strong><?=$anuncio->getTitulo()?></a></p>
                        <p style="margin-left:4%;"><a href="VerAnuncio.php?id<?=$anuncio->getId()?>"><strong>Precio:</strong><?=$anuncio->getPrecio()?></a></p>
                    </div>
                    <?php if($fotos = explode(";", $anuncio->getFoto())): ?>
                        <a href="VerAnuncio.php?id=<?=$anuncio->getId()?>"><img class="productos" src="fotosAnuncios/<?=$fotos[0]?>"></a>
                        <?php else: ?>
                        <a href="VerAnuncio.php?id=<?=$anuncio->getId()?>"><img class="productos"src="fotosAnuncios/<?=$anuncio->getFoto()?>"></a>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['email']) && $_SESSION['id'] == $anuncio->getIdUsuario()): ?>
                        <button><a href="borrarAnuncio.php?id=<?=$anuncio->getId()?>">Borrar</a></button>
                        <button style="margin-left: 2%"><a href="modificarAnuncio.php?id=<?=$anuncio->getId()?>">Editar</a></button>
                    <?php endif; ?>  
                </div>
            <?php endforeach; ?>
        </div>
        <form class="paginacion" action="index.php" method="post" style="padding-left: 39%">
            <input type="hidden" name="pagina" value="<?=$paginaActual?>">
            <input type="submit" name="antPag" value="Pagina Anterior">
            </form>
        <form class = "paginacion" action="index.php" method="post" style="margin-left: 10px">
            <input type="hidden" name="pagina" value="<?=$paginaActual?>">
            <input type="submit" name="sigPag" value="Siguiente Pagina">
        </form>
    <?php endif; ?>

    <?php if(isset($anunciosId) && $_SESSION['cargarPorId'] == true): ?>
        <div class="contenedor">
            <?php foreach($anunciosId as $anuncioId): ?>
                <div class="anuncio">
                    <div class="titulos">
                        <p><a href="VerAnuncio.php?id=<?=$anuncioId->getId()?>"><strong>Producto:</strong><?=$anuncioId->getTitulo()?></a></p>
                        <p><a href="VerAnuncio.php?id=<?=$anuncioId->getId()?>"><strong>Precio:</strong><?=$anuncioId->getPrecio()?></a></p>
                    </div>
                    <?php if($fotos = explode(";", $anuncioId->getFoto())): ?>
                        <a href="VerAnuncio.php?id=<?=$anuncioId->getId()?>"><img class="productos" src="fotosAnuncios/<?=$fotos[0]?>"></a>
                    <?php else: ?>
                        <a href="VerAnuncio.php?id=<?=$anuncioId->getId()?>"><img class="productos" src="fotosAnuncios/<?=$anuncioId->getFoto()?>"></a>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['email']) && $_SESSION['id'] == $anuncioId->getIdUsuario()): ?>
                        <button><a href="borrarAnuncio.php?id=<?=$anuncioId->getId()?>">Borrar</a></button>
                        <button style="margin-left: 2%"><a href="modificarAnuncio.php?id=<?=$anuncioId->getId()?>">Editar</a></button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <form class = "paginacion" action="index.php" method="post" style="padding-left: 40%">
            <input type="hidden" name="pagina" value="<?=$paginaActual?>">
            <input type="submit" name="antPag" value="Pagina Anterior">
            </form>
        <form class = "paginacion" action="index.php" method="post" style="margin-left: 10px">
            <input type="hidden" name="pagina" value="<?=$paginaActual?>">
            <input type="submit" name="sigPag" value="Siguiente Pagina">
        </form>
    <?php endif; ?>
</body>
</html>