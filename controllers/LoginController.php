<?php 
namespace Controllers;

use Mail\Mail;
use Model\Usuario;
use MVC\Router;

class LoginController{


    public static function login(Router $router) {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            $auth= new Usuario($_POST);

            $alertas = $auth->validarLogin();
            
            if (empty($alertas)) {
                $usuario= Usuario::where('email',$auth->email);
                if (!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error','El usuario no existe o no confirmado');
                }else{
                    if (password_verify($_POST['password'],$usuario->password)) {
                        session_start();
                        $_SESSION['id']= $usuario->id;
                        $_SESSION['nombre']= $usuario->nombre;
                        $_SESSION['email']= $usuario->email;
                        $_SESSION['login']= true;
                        header('Location:/dashboard');
                    }else{
                        Usuario::setAlerta('error','Clave incorrecta');
                    }
                }
            }
            $alertas= Usuario::getAlertas();
        }


        $router->render('auth/login',[
            'titulo' => 'Iniciar Sesion',
            'alertas' => $alertas
        ]);
    }

    public static function logout(Router $router) {
      
        session_start();
        $_SESSION=[];
        header('Location:/');
    }
    public static function crear(Router $router) {
        $usuario = new Usuario();
        $alertas =[];
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
          
           if (empty($alertas)) {
            $existeUsuario = Usuario::where('email',$usuario->email);
            if ($existeUsuario) {
                Usuario::setAlerta('error','El usuario ya esta resgistrado');
                $alertas = Usuario::getAlertas();
            }else{
                $usuario->hashPassword();
                unset($usuario->password2);
                $usuario->crearToken();
                $resultado = $usuario->guardar();
                $mail= new Mail($usuario->email,$usuario->nombre,$usuario->token);
                $mail->enviarMail();
                if ($resultado) {

                    header('Location:/mensaje');
                }
            }
           }
        }

        $router->render('auth/crear',[
            'titulo' => 'Crear tu cuenta Uptask',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    public static function olvide(Router $router) {
            $alertas=[];
        if ($_SERVER['REQUEST_METHOD']==='POST') {
           $usuario = new Usuario($_POST); 
           $alertas = $usuario->validarMail();
           if (empty($alertas)) {
                $usuario= Usuario::where('email',$usuario->email);
                if ($usuario && $usuario->confirmado) {
                    $usuario->crearToken();
                    unset($usuario->passwork2);
                    $usuario->guardar();
                    $mail = new Mail($usuario->email,$usuario->nombre,$usuario->token);
                    $mail->enviarInstrucciones();
                    Usuario::setAlerta('exito','Hemos enviado las instrucciones a tu correo');
                } else {
                    Usuario::setAlerta('error','El usuario noo existe o no esta confirmado');
                } 
                $alertas = Usuario::getAlertas();
                
           }
        }
        $router->render('auth/olvide',[
            'titulo' => 'Recuperar Uptask',
            'alertas'=> $alertas
        ]);
        
        
    }
    public static function reestablecer(Router $router) {
             $mostrar=true;
            $token = s($_GET['token']);
            if (!$token) header('Location:/');

            $usuario= Usuario::where('token',$token);

            if (empty($usuario)) {
                $mostrar = false;
                Usuario::setAlerta('error','Token no válido');
            }  
                     
            
            if ($_SERVER['REQUEST_METHOD']==='POST') {
                $usuario->sincronizar($_POST);
                $alertas=$usuario->validarPassword();
                if (empty($alertas)) {
                    $usuario->token = null;
                    $usuario->hashPassword();
                    unset($usuario->password2);
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        Usuario::setAlerta('exito','Se ha reestablecido tu clave');
                        $mostrar = false;
                    }
                    
                }
            }
            $alertas =Usuario::getAlertas();
        $router->render('auth/reestablecer',[
            'titulo' => 'Reestablecer Uptask',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        
        ]);

        
    }
    public static function mensaje(Router $router) {
        

        $router->render('auth/mensaje',[
            'titulo' => 'Confirmar Uptask'
        ]);
    }
    public static function confirmar(Router $router) {
            $token = s($_GET['token']);
            if (!$token) header('Location:/');
            $usuario= Usuario::where('token',$token);
            if (empty($usuario)) {
                Usuario::setAlerta('error','Token no válido');
            }else{
                $usuario->confirmado=1;
                unset($usuario->password2);
                $usuario->token = null;
                $usuario->guardar();
                Usuario::setAlerta('exito', 'Tu cuenta a sido confirmada');
            }
            $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar',[
            'titulo' => 'Iniciar Uptask',
            'alertas' => $alertas
        ]);

        
    }
}


?>