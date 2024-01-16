<?php
session_start();
require_once 'modelos/ConexionDB.php';
require_once 'modelos/UsuariosDAO.php';
require_once 'modelos/Usuario.php';
require_once 'modelos/AnunciosDAO.php';
require_once 'modelos/Anuncio.php';

//Conexion de la base de datos
$conexionDB = new ConexionDB("id21583343_marcos", "12345Aa_","localhost", "id21583343_tienda");
$conn = $conexionDB->getConexion();

//Obtenemos el id del anuncio que se ha pasado por el metodo get 
$idAnuncio = htmlentities($_GET['id']);

//Y con este id buscaremos si hay un anuncio con ese id y si lo hay borraremos ese mensaje
$anunciosDAO = new AnunciosDAO($conn);
if($anuncio = $anunciosDAO->getById($idAnuncio)){
    $anunciosDAO->borrarAnuncios($anuncio->getId());
}

header('location: index.php');
?>