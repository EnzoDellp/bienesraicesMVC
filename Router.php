<?php
namespace MVC;

class Router{

    public $rutasGET=[];
    public $rutasPOST=[];

    public function get($url,$fn){
        $this->rutasGET[$url]=$fn;
    }


    public function comprobarRutas(){
        $urlActual = $_SERVER['REQUEST_URI'];
        $metodo=$_SERVER["REQUEST_METHOD"];
      

      if($metodo==="GET"){
        $fn=$this->rutasGET[$urlActual] ?? null;
      }
      if($fn){
        //la URL existe y hay una funcion asociada

        
        call_user_func($fn,$this);
      }else{
        echo"pagina no encontrada";
      }

    }
}

