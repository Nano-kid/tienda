<?php
require_once 'modelos/ConexionDB.php';
require_once 'modelos/UsuariosDAO.php';
require_once 'modelos/Usuario.php';
require_once 'funciones.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //Establecemos conexion con la base de datos.
    $ConexionDB = new ConexionDB("id21583343_marcos", "12345Aa_","localhost", "id21583343_tienda");
    $conn = $ConexionDB->getConexion();

    //Recogemos los datos del formulario. 
    $email = htmlentities($_POST['email']);
    $passsword = htmlentities($_POST['password']);
    $passswordCifrada = password_hash($passsword, PASSWORD_DEFAULT);
    $nombre = htmlentities($_POST['nombre']);
    $telefono = htmlentities($_POST['telefono']);
    $poblacion = htmlentities($_POST['poblacion']);

    //Comprobamos que el email introducido no coincide con ninguno de los que ya estan en la base de datos
    /*$usuariosDAO = new UsuariosDAO($conn);
    $usuarios = array();
    $usuarios = $usuariosDAO->cargarUsuarios();
    for($i = 0;$i < (sizeof($usuarios)); $i++){
        if($email == $usuarios[$i]->getEmail()){
            guardarError("Ese email ya esta registrado");
            $email = null;
        }
    }*/

    /*Comprobamos que la longitud de la contreseña y en caso de ser la adecuada la codificaremos 
    if(strlen($passsword) >= 4){
        $passswordCifrada = password_hash($passsword, PASSWORD_DEFAULT);
    }else{
        $error = "La contraseña debe ser de almenos cuatro caracteres";
    }*/

    //En caso de que el email sea correctos procedemos a crear un nuevo usuario
    $usuariosDAO = new UsuariosDAO($conn);
    $usuario = new Usuario();
    if(!empty($email) && !empty($passswordCifrada)){
        //Asignamos los diferenetes valores recogidos en el formulario para crear el nuevo usuario a añadr
        $usuario->setEmail($email);
        $usuario->setPassword($passswordCifrada);
        $usuario->setNombre($nombre);
        $usuario->setTelefono($telefono);
        $usuario->setPoblacion($poblacion);
        $usuario->setSid(sha1(rand()+time()), true);
        /*Comprobamos que el email introducido no coincide con ninguno de los que ya estan 
        en la base de datos y en caso de que no haya ningun usuario con ese email lo registramos en la base de datos*/
        if(!$usuariosDAO->getByEmail($email)){
            $usuariosDAO->insertarUsuario($usuario);
        }else{
            guardarError("Ese email ya esta registrado");
        }
    }else{
        guardarError("Debes rellenar por lo menos los campos email y password");
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

        h1{
            text-align: center;
            margin-top: 0;
            margin-bottom: 3%;
            border-bottom: solid black 2px;
            padding:5%;
        }

        .formulario{
            font-size:30px;
            margin-top:2%;
            margin-left: 35%;
            margin-right: 35%;
            background-color:white;
            border: solid black 2px;
            border-radius: 10px;
            width: 29%;
        }

        .error{
            color:red;
            display: block;
            padding: 5px;
            margin: auto;
            width: 80%;
            background-color: white;
            border: 1px solid red;
            text-align: center;
            margin-top: 20px;
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

        #boton{
            height: 40px;
            margin-left: 36%;
            width: 30%;
            margin-top:15px;
            margin-bottom:15px;
        }
        
        #parrafo{
            text-align:center;
        }
    </style>
</head>
<body>
    <?php mostrarError() ?>
    <div class="formulario">
            <h1>Registrarse</h1>
                <?php if(isset($usuario)): ?>
                        <form action="insertarUsuario.php" method="post">
                            <label for="email">Email:</label>
                            <input type="text" name="email" id="email" value="<?=$usuario->getEmail()?>"><br>
                            <label for="password">Passoword:</label>
                            <input type="password" name="password" id="password" minlength="4" value="<?=$passsword?>"><br>
                            <label for="nombre">Nombre:</label>
                            <input type="text" name="nombre" id="nombre" value="<?=$usuario->getNombre()?>"><br>
                            <label for="telefono">Telefono:</label>
                            <input type="text" name="telefono" id="telefono" value="<?=$usuario->getTelefono()?>"><br>
                            <label for="poblacion">Poblacion:</label>
                            <input type="text" name="poblacion" id="poblacion" value="<?=$usuario->getPoblacion()?>"><br>
                            <input type="submit" id="boton" value="Enviar">
                        </form>
                <?php else: ?>
                        <form action="insertarUsuario.php" method="post">
                            <label for="email">Email:</label>
                            <input type="text" name="email" id="email"><br>
                            <label for="password">Passoword:</label>
                            <input type="password" name="password" id="password" minlength="4"><br>
                            <label for="nombre">Nombre:</label>
                            <input type="text" name="nombre" id="nombre"><br>
                            <label for="telefono">Telefono:</label>
                            <input type="text" name="telefono" id="telefono"><br>
                            <label for="poblacion">Poblacion:</label>
                            <input type="text" name="poblacion" id="poblacion"><br>
                            <input type="submit" id="boton" value="Enviar">
                        </form>
                <?php endif; ?>
            <p id="parrafo">Pulse aqui para <a href="index.php">volver</a></p>
    </div>
</body>
</html>