<?php 

namespace Controllers;

use MVC\Router;
use Model\Usuario;
use Model\Proyecto;

class DashboradController {
    public static function index(Router $router) {

        if(!isset($_SESSION)) session_start();
        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioId', $id); // BelongsTo es similar a where con la diferencia que muestra todas las coincidencias de la db

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router) {

        if(!isset($_SESSION)) session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);

            // Validacion
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)) {
                // Generar una URL unica
                $proyecto->url = md5(uniqid());

                // Almacenar el Creador Del proyecto
                $proyecto->propietarioId = $_SESSION['id'];
                
                // Guardar Proyecto
                $proyecto->guardar();

                // Redireccionar
                header('Location: /proyecto?id=' . $proyecto->url );
            }
        }

        $router->render('dashboard/crear-proyecto', [
            'alertas' => $alertas,
            'titulo' => 'Crear Proyecto'
        ]);
    }

    public static function proyecto(Router $router) {

        if(!isset($_SESSION)) session_start();
        isAuth();

        $url = s($_GET['id']);
        if(!$url) header('Location: /dashboard');

        // Revisar que la persona que visita el proyecto, es quien lo creo
        $proyecto = Proyecto::where('url', $url);
        if($proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }


        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function perfil(Router $router) {

        if(!isset($_SESSION)) session_start();
        $id = $_SESSION['id'];
        $usuario = Usuario::find($id);
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);
            
            $alertas = $usuario->validarPerfil();

            if(empty($alertas)) {
                // Verificar que el email no exista
                $existeUsuario = Usuario::where('email', $usuario->email);

                // Si el usuario existe y si el id del usuario que buscamos mediante email es diferente al del usuario que quiere cambiar su correo
                if($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    // Mostrar mensaje de error
                    $alertas = Usuario::setAlerta('error', 'Email no valido, usuario existente');
                } else {
                    // Guardar el usuario
                    $usuario->guardar();

                    $alertas = Usuario::setAlerta('exito', 'Guardado Correctamente');

                    // Actualizar nombre en la sesion
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }

        $alertas = $usuario->getAlertas();
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function cambiar_password(Router $router) {

        if(!isset($_SESSION)) session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);

            // Sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevo_password();

            if(empty($alertas)) {
                $resultado = $usuario->comprobarPassword();

                if($resultado) {
                    // Asignar el nuevo password
                    $usuario->password = $usuario->password_nuevo;

                    // Eliminar campos innecesarios
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);
                    unset($usuario->password2);

                    // hashpassword 
                    $usuario->hashPassword();

                    // Actualizar Password
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        Usuario::setAlerta('exito', 'Guardado Correctamente');
                        $alertas = $usuario->getAlertas();
                    }
                } else {
                    Usuario::setAlerta('error', 'Password Incorrecto');
                    $alertas = $usuario->getAlertas();
                }
            }
        }

        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas
        ]);
    }

    public static function eliminar_proyecto() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!isset($_SESSION)) session_start();
            // Validar que el proyecto exista
            $proyecto = Proyecto::where('url', $_POST['urlProyecto']);

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al eliminar la proyecto'
                ];
                echo json_encode($respuesta);
                return;
            }

            
            $resultado = $proyecto->eliminar();
            $resultado = [
                'resultado' => $resultado,
                'mensaje' => 'Proyecto Eliminado Correctamente',
                'tipo' => 'exito',
                'redirect' => '/dashboard'
            ];

            echo json_encode($resultado);
        }
    }
}