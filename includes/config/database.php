<?php

function conectarDB():mysqli{
    $db= new mysqli("localhost","root","adrianferrari15","bienesraices_crud");
    if (!$db) {
        echo "Error no se pudo conectar";
       exit; 
    }
    return $db;
    echo"Se pudo conectar la la bd";
}
