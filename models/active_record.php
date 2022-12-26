<?php

namespace Model;

class Active_Record{
 
    //bade de datos
    protected static $db;

    protected static $columnasDB=[];
    protected static $tabla='';
    //errores
    protected static $errores=[];

    //definir la conexion a la BD
    public static function setDB($database){
        self::$db = $database;
    }

 
    public function guardar() {
        if(!is_null($this->id)) {
            // actualizar
            $this->actualizar();
        } else {
            // Creando un nuevo registro
            $this->crear();
        }
    }

    public function crear(){
     
        $atributos=$this->sanitizarAtributos();
     //*insertar en la base de datos
     //?forma vista en el curso
    //  $query="INSERT INTO propiedades(";
    //  $query.=join(', ',array_keys($atributos));
    //  $query.=") VALUES ('";
    //  $query.=join("','",array_values($atributos));
    //  $query.="')";

     //?forma entendible
    $columnas = join(', ',array_keys($atributos));
    $filas = join("', '",array_values($atributos));
    $query = "INSERT INTO ".static::$tabla ."($columnas) VALUES ('$filas')";
    
    //   debugear($query);
     $resultado=self::$db->query($query);
            //mensaje de exito o error
    if ($resultado) {
        //reedirecion al usuario
        header("location:/admin/index.php?resultado3=1");
          }
    }

    public function actualizar() {

        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 "; 

        $resultado = self::$db->query($query);

        if($resultado) {
            // Redireccionar al usuario.
            header('Location: /admin/index.php');
        }
    }
    //eliminar registro
    public function eliminar() {
        // Eliminar el registro
        $query = "DELETE FROM "  . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);

        if($resultado) {
            $this->borrarImagen();
            header('location: /admin/index.php');
        }
    }  
    //identificar y unir los atributos de la BD
    public function atributos(){
        $atributos=[];
        foreach(static::$columnasDB as $columna){
            if ($columna ==="id") continue;
            $atributos[$columna]=$this->$columna;
        }
            return $atributos;
       
    }

    public function sanitizarAtributos(){
        $atributos=$this->atributos();
        $sanetizado=[];


        foreach($atributos as $key => $value){
            $sanetizado[$key]= self::$db -> escape_string($value);
        }
        return$sanetizado;

    }

    //subida de archivos
    public function setImagen($imagen){
        //Elimina la imagen anterior
        if(!is_null($this->id)){
           $this->borrarImagen();
        }
        //asignar el atributo de imagen el nombre de la imagen
        if ($imagen){
            $this->imagen=$imagen;
        }
    }
    //Eliminar el archivo
    public function borrarImagen(){
      //Elimina la imagen anterior
      if(!is_null($this->id)){
        //comprobar si existe la imagen
        $existeArchivo=file_exists(CARPETA_INAGENES.$this->imagen);
        if ($existeArchivo){
            unlink(CARPETA_INAGENES.$this->imagen);

        }
    }  
    }
//validacion

public static function getErrores(){
    return static::$errores;
}


public function validar(){
    static::$errores=[];
      return static::$errores;
     
}

//lista todos los registros

public static function all(){
    $query="SELECT * FROM ".static::$tabla;
    

  $resultado= self::consultarSQL($query);

    return $resultado;
}

//obtiene determinado numero de registros
public static function get ($cantidad){
$query="SELECT * FROM ".static::$tabla."LIMIT".$cantidad;
  
$resultado= self::consultarSQL($query);

    return $resultado;
}



//busca una propiedad por su id
public static function find($id) {
    $query = "SELECT * FROM " . static::$tabla  ." WHERE id = ${id}";

    $resultado = self::consultarSQL($query);

    return array_shift( $resultado ) ;
}


public static function consultarSQL($query){

    //consultar bd
    $resultado=self::$db->query($query);
    
    //iterar los resultados
    $array=[];
    while ($registro=$resultado->fetch_assoc()){
        $array[]=static::crearObjeto($registro);
    }
    
    //liberar la memoria
    $resultado->free();
    //retornar los resultados
    return $array;
}
protected static function crearObjeto($registro){

    $objeto=new static;

    foreach($registro as $key => $value){

        if(property_exists($objeto,$key)){

            $objeto->$key=$value;
        }
    }
    return $objeto;

}

//sincroniza el objeto en memoria con los cambios realizaods por el usuario
    public function sincronizar($args = [] ){
        foreach($args as $key => $value){
            if(property_exists($this,$key) && !is_null($value)){
                $this->$key=$value;
            }
        }
    }
}