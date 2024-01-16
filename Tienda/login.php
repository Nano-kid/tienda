<?php
require_once 'modelos/ConexionDB.php';
require_once 'modelos/UsuariosDAO.php';
require_once 'modelos/Usuario.php';
require_once 'funciones.php';

session_start();
$conexionDB = new ConexionDB("id21583343_marcos", "12345Aa_","localhost", "id21583343_tienda");
$conn = $conexionDB->getConexion();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //Guardamos los datos obtenidos en el formulario
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)){
        guardarError("Debes rellenar el email y la contraseña");
        header('location: index.php');
    }

    //Llamamos al metodo conseguir por id para obtener el usuario cuyo email sea el que se ha pasado previamente a traves del formulario
    $usuariosDAO = new UsuariosDAO($conn);
    $usuario = $usuariosDAO->getByEmail($email);

    //Comprobamos que tanto el email y contraseña obtenidos con el formulario coinciden con un usuario que este registrado 
    if($email == $usuario->getEmail() && password_verify($password, $usuario->getPassword())){
        $_SESSION['email'] = $usuario->getEmail();
        $_SESSION['id'] = $usuario->getId();
        $_SESSION['password'] = $usuario->getPassword();
        setcookie('sid',$usuario->getSid(), time()+24*60*60,"/");
    }else{
        guardarError("El email o la contraseña son incorrectos");
    }
    header('location: index.php');
}

?>