<?php 
namespace  Controllers;

use Model\Proyectos;
use Model\Usuario;
use MVC\Router;

class DashboardController{
    public static function index(Router $router) {
        session_start();
        isAuth();
        $id= $_SESSION['id'];
        $proyectos = Proyectos::belongsTo('propietarioid',$id);
        $router->render('dashboard/index',[
            'titulo' => 'Proyectos',
            'proyectos'=>$proyectos
        ]);
    }
    public static function crear_proyecto(Router $router) {
        $alertas =[];    
        session_start();

            isAuth();

            if ($_SERVER['REQUEST_METHOD']==='POST') {
                $proyecto = new Proyectos($_POST);
                
                $alertas = $proyecto->validarProyecto();
                if (empty($alertas)) {
                    $proyecto->url = md5(uniqid());
                    $proyecto->propietarioid = $_SESSION['id'];
                    $proyecto->guardar();
                    header('Location:/proyecto?id='.$proyecto->url);
                }
            }

        $router->render('dashboard/crear-proyecto',[
            'titulo'=>'Crear Proyecto',
            'alertas' =>$alertas
        ]);
    }
    public static function perfil(Router $router) {
        $alertas= [];    
        session_start();
        isAuth();
        $usuario = Usuario::find($_SESSION['id']);
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarPerfil();
            if (empty($alertas)) {
                $existeUsuario = Usuario::where('email',$usuario->email);
                if ($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    Usuario::setAlerta('error','Email existente');
                    
                }else {
                    
                    $usuario->guardar();
                    Usuario::setAlerta('exito','Guardado correctamente');
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('dashboard/perfil',[
            'titulo'=>'Perfil',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }
    public static function cambiar_password(Router $router) {
        $alertas= [];    
        session_start();
        isAuth();
        $usuario = Usuario::find($_SESSION['id']);
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            $usuario = Usuario::find($_SESSION['id']);
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevoPassword();
            if (empty($alertas)) {
                $resultado = $usuario->comprobarPassword();
                if ($resultado) {
                    $usuario->password = $usuario->password_nuevo;
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);
                    $usuario->hashPassword();
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        Usuario::setAlerta('exito','Guardado correctamente');
                        
                    }
                   
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('dashboard/cambiar-password',[
            'titulo'=>'Cambiar Clave',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }
    public static function proyecto(Router $router) {
            session_start();
            isAuth();
            $token = s($_GET['id']);
          
            if (!$token) header('Location:/dashboard');
            $proyecto = Proyectos::where('url',$token);
            if ($proyecto->propietarioid !== $_SESSION['id']) header('Location:/dashboard');
        $router->render('dashboard/proyecto',[
            'titulo'=>$proyecto->proyecto
        ]);
    }
}


?>