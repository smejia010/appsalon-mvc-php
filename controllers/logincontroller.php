<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {

    
    public static function login(Router $router){
        $alertas = [];
        $auth = new Usuario;
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);

            $alertas = $auth-> validarLogin();
            
            if(empty($alertas)){
                //COMPROBAR QUE EXISTA EL USUARIO
                $usuario = Usuario::where('email', $auth->email);
                if($usuario) {
                    //verificar usuario
                    if ($usuario -> comprobarPasswordAndVerificado($auth->password)){
                        //autenticar el usuario
                        session_start();
                        $_SESSION['id'] = $usuario -> id;
                        $_SESSION['nombre'] = $usuario -> nombre . " " . $usuario->apellido;
                        $_SESSION["email"] = $usuario -> email;
                        $_SESSION["login"] = true;

                        //redireccionamiento

                        if($usuario->admin){
                            $_SESSION["admin"] = $usuario->admin ?? null;
                            header('location:/admin');
                        }else {
                            header('location:/cita');
                        }

                        
                    }
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe');
                }
            }

        }

        $alertas = Usuario::getAlertas();

        $router-> render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth 
        ]);
    }

    public static function logout(){
        session_start();

        //ASIGNO TODO A UN ARREGLO VACIO Y ASÍ LIMPIO LA SESION
        $_SESSION = [];

        header('Location: /');


    }

    public static function olvide(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){ 
                $usuario = Usuario::where('email', $auth->email);
                
                if($usuario && $usuario->confirmado === "1"){ 
                    //generar token
                    $usuario-> crearToken();
                    $usuario-> guardar();

                    //Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email-> enviarInstrucciones();


                    //Alerta de exito
                    Usuario::setAlerta('exito', 'Instrucciones enviadas, revisa tu correo');

                }else {
                    Usuario::setAlerta('error','El usuario no existe o no está confirmado');
                    
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router-> render('auth/olvide', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){
        $alertas = [];
        $token = s($_GET['token']);
        $error = false;
        //buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){ 
            Usuario::setAlerta('error','Token no valido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){ 
            //Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)){ 
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario-> hashPassword();
                $usuario->token = null;

                $resultado= $usuario->guardar();
                if($resultado){
                    header('location:/');
                }

                
            }
        }

        //debuguear($usuario);
        $alertas = Usuario::getAlertas();
        $router -> render('auth/recuperar-password', [
            'alertas'    => $alertas,
            'error' => $error
        ]);
    }

    public static function crearcuenta(Router $router){
        $usuario = new Usuario;
        
        //ALERTAS VACIAS
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            //REVISAR QUE ALERTAS ESTÉ VACÍO
            if(empty($alertas)){
                //VERIFICAR QUE EL USUARIO NO ESTÉ REGISTRADO
                $resultado = $usuario->existeUsuario();
                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                } else {
                    //hashear password
                    $usuario -> hashPassword();

                    //GENERAR TOKEN UNICO
                    $usuario -> crearToken();

                    //ENVIAR EL EMAIL
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    //CREAR USUARIO 
                    $resultado = $usuario->guardar();
                    if($resultado) {
                        header('location: /mensaje');
                    }
                }
            }
        }

        $router-> render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje');
    }
    


    public static function confirmar(Router $router) {
        
        $alertas = [] ;
        $token = s($_GET['token']);
        
        
        $usuario = Usuario::where('token',$token);

        
        if(empty($usuario)) {
            //MOSTRAR MENSAJE DE ERROR
            Usuario::setAlerta('error', 'Token no valido');
            
        } else {
            $usuario->confirmado = 1;
            $usuario->token  = '';
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }
        
        //OBTENER ALERTAS
        $alertas = Usuario::getAlertas(); 

        //RENDERIZAR LA VISTA
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
            
        ]);
    }
}