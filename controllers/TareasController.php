<?php 
namespace Controllers;

use Model\Proyectos;
use Model\Tarea;

class TareasController{

    public static function index() {
        $proyectoid = s($_GET['id']);
        
        if (!$proyectoid) header('Location:/dashboard');
        
        $proyecto = Proyectos::where('url',$proyectoid);

        session_start();
     
        if (!$proyecto || $proyecto->id !== $_SESSION['id']) header('Location:/404');
        
        $tareas = Tarea::belongsTo('proyectoid',$proyecto->id);
       
        echo json_encode(['tareas' => $tareas]);


       
    }
    public static function crear() {
        
        if ($_SERVER['REQUEST_METHOD']==='POST') {
             session_start();
             $proyectoid = $_POST['proyectoid'];
             $proyecto = Proyectos::where('url',$proyectoid);
            if (!$proyecto || $proyecto->propietarioid!==$_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje'=> 'Hubo un error al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }
            
            $tarea= new Tarea($_POST);
            $tarea->proyectoid = $proyecto->id;
            $resultado = $tarea->guardar();
            if ($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $resultado['id'],
                    'mensaje' => 'Tarea creada correctamente',
                    'proyectoid' => $proyecto->id
                ];
                echo json_encode($respuesta);
                
            }

        }

       
    }
    public static function actualizar() {
        if ($_SERVER['REQUEST_METHOD']==='POST') {
             $proyecto = Proyectos::where('url',$_POST['proyectoid']);
             session_start();
             if (!$proyecto || $proyecto->propietarioid!==$_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje'=> 'Hubo un error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }
            $tarea = new Tarea($_POST);
            $tarea->proyectoid =  $proyecto->id;
            $resultado = $tarea->guardar();
        
            if ($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'proyectoid' => $proyecto->id,
                    'mensaje' => 'Actualizado correctamente'
                ];
                echo json_encode($respuesta);
                
            }
        }

       
    }
    public static function eliminar() {
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            $proyecto = Proyectos::where('url',$_POST['proyectoid']);
            session_start();
            if (!$proyecto || $proyecto->propietarioid!==$_SESSION['id']) {
               $respuesta = [
                   'tipo' => 'error',
                   'mensaje'=> 'Hubo un error al actualizar la tarea'
               ];
               echo json_encode($respuesta);
               return;
           }
           $tarea = new Tarea($_POST);
           $resultado = $tarea->eliminar();
           
           if ($resultado) {
               $respuesta = [
                   'resultado' => $resultado,
                   'tipo' => 'exito',
                   'mensaje' => 'Eliminado correctamente'
               ];
               echo json_encode($respuesta);
               
           }
        }

       
    }


}

?>