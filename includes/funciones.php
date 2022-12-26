<?php
define("TEMPLATES_URL",__DIR__."/templates");
define("FUNCIONES_URL",__DIR__."funciones.php");
define('CARPETA_INAGENES',__DIR__.'/../imagenes/');

function inculirtemplate(string $nombre, bool $inicio = false){

    include TEMPLATES_URL."/${nombre}.php";

};

function estaAutenticado(){
    session_start();
    if(!$_SESSION["login"]){
        header("location:/index.php");
    }
}

function debugear($variable){
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

//escapa/sanetizar el HTML

function s($html):string{
    $s=htmlspecialchars($html);
    return $s;
}

//validar tipo de contenido

function validarTipoContenido($tipo){
    $tipos=["vendedor","propiedad"];
    return in_array($tipo,$tipos);
}

//muestra los mensajes

function mostrarNotificacion($codigo){
    $mensaje="";
    switch ($codigo){
         case 1:
            $mensaje="creado correctamente";
            break;
            case 2:
                $mensaje="actualizado correctamente";
                break;
                case 3:
                    $mensaje="Eliminado correctamente";
                    break;
        default:
        $mensaje=false;
           
        return $mensaje;
    }
}