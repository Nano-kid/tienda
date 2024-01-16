<?php

function generarNombre($nombreOriginal){
    $partes = explode(".", $nombreOriginal);
    $nombreFinal = md5(time() + rand());
    $extension = $partes[sizeof($partes)-1];
    return $nombreFinal.".".$extension;
}

function guardarError($error){
    $_SESSION['error'] = $error;
}

function mostrarError(){
    if(isset($_SESSION['error'])){
        echo '<div class="error">'.$_SESSION['error'].'</div>';
        unset($_SESSION['error']);
    } 
}
?>