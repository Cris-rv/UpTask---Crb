<?php 

namespace Controllers;

use MVC\Router;
use Model\Usuario;
use Classes\Email;

class LoginController {
    public static function login(Router $router) {

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);

            $alertas = $usuario->validarLogin();

            if(empty($alertas)) {
                // Verificar que el usuario exista
                $usuario = Usuario::where('email', $usuario->email);

                if(!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'Usuario no encontrado o no esta confirmado');
                } else {
                    // El usuario existe
                    if( password_verify($_POST['password'], $usuario->password) ) {
                        // Iniciar la Sesion
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionar
                        header('Location: /dashboard');
                    } else {
                        Usuario::setAlerta('error', 'Password Incorrecto');
                    }
                }
 
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesion',
            'alertas' => $alertas
        ]);
    }

    public static function crear(Router $router) {

        $usuario = new Usuario;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $usuario->sincronizar($_POST);
        $alertas = $usuario->validarCuenta();

        if(empty($alertas)) {
            $existeUsuario = Usuario::where('email', $usuario->email);

            if($existeUsuario) {
                Usuario::setAlerta('error', 'El usuario ya esta registrado');
                $alertas = Usuario::getAlertas();
            } else {
                // Hashear El Password
                $usuario->hashPassword();

                // Eliminar password2
                unset($usuario->password2);

                // Generar el Token
                $usuario->crearToken();

                //Crear un nuevo Usuario
                $resultado = $usuario->guardar();
                if($resultado) {
                    // Enivar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    header('Location: /mensaje');
                }
            }
        }

        }

        $router->render('auth/crear', [
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router) {
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)) {
                // Buscar el usuario
                $usuario = Usuario::where('email', $usuario->email);

                if($usuario && $usuario->confirmado) {
                    
                    // Generar un nuevo Token y eliminar password2
                    $usuario->crearToken();
                    unset($usuario->password2);

                    // Actualizar el usuario
                    $usuario->guardar();

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Imprimir Alerta
                    Usuario::setAlerta('exito', 'Las instrucciones de recuperacion de password han sido enviadas a tu email');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide', [
            'titulo' => 'Olvide Password',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router) {

        $alertas = [];
        $mostrar = true;
        $token = s($_GET['token']);
        if(!$token) header('Location: /');

        // Identificar el usuario con este token
        $usuario = Usuario::where('token', $token);

        // Si el usuario no se encuentra por el token entonces
        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no Valido');
            $mostrar = false;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Añadir el nuevo password
        $usuario->sincronizar($_POST);

        // Validar el password
        $alertas = $usuario->validarPassword();

        if(empty($alertas)) {
            // Hasheamos el password y eliminar el password2
            $usuario->hashPassword();
            unset($usuario->password2);

            // Eliminamos el token
            $usuario->token = null;

            // Guardamos Cambios
            $resultado = $usuario->guardar();

            if($resultado) {
                header('Location: /');
            }
        }
        
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje', [
            'titulo' => 'Mensaje'
        ]);
    }

    public static function confirmar(Router $router) {
        // Creamos la variable de alertas
        $alertas = [];

        // Obtenemos el token del REQUES METHOD de tipo GET
        $token = s($_GET['token']);

        if(!$token) header('Location: /');

        // Comprobamos que el usuario exista mediante el token
        $usuario = Usuario::where('token', $token);

        // Si no encuentra el usuario mediante el token entonces no es valido caso contrario confirmamos la cuenta, eliminamos el token y guardamos los cambios
        if(empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'El token no es valido');            
        } else {
            // Confirmar la cuenta
            $usuario->confirmado = "1";
            // Eliminamos el token
            $usuario->token = null;
            // Eliminamos el campo password2
            unset($usuario->password2);
            // Guardamos los cambios
            $usuario->guardar();
            // Mostramos alerta de exito
            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }

        //Mostrar las alertas
        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            'titulo' => 'Confirmar Cuenta',
            'alertas' => $alertas
        ]);
    }

    public static function logout(Router $router) {
        if(!isset($_SESSION)) {
        session_start();
        }
        $_SESSION = [];
        header('Location: /');
    }
}