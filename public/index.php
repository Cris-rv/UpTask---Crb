<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\DashboradController;
use MVC\Router;
use Controllers\LoginController;
use Controllers\ProyectoController;
use Controllers\TareaController;

$router = new Router();

// Login
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

// Crear cuenta
$router->get('/crear', [LoginController::class, 'crear']);
$router->post('/crear', [LoginController::class, 'crear']);

// Formulario de olvide mi password
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);

// Colocar el nuevo password
$router->get('/reestablecer', [LoginController::class, 'reestablecer']);
$router->post('/reestablecer', [LoginController::class, 'reestablecer']);

// Confirmacion de cuenta
$router->get('/mensaje', [LoginController::class, 'mensaje']);
$router->get('/confirmar', [LoginController::class, 'confirmar']);

// Zona de proyectos
$router->get('/dashboard', [DashboradController::class, 'index']);
$router->get('/crear-proyecto', [DashboradController::class, 'crear_proyecto']);
$router->post('/crear-proyecto', [DashboradController::class, 'crear_proyecto']);
$router->get('/proyecto', [DashboradController::class, 'proyecto']);
$router->get('/perfil', [DashboradController::class, 'perfil']);
$router->post('/perfil', [DashboradController::class, 'perfil']);
$router->get('/cambiar-password', [DashboradController::class, 'cambiar_password']);
$router->post('/cambiar-password', [DashboradController::class, 'cambiar_password']);

// API para las tareas
$router->get('/api/tareas', [TareaController::class, 'index']);
$router->post('/api/tarea', [TareaController::class, 'crear']);
$router->post('/api/tarea/actualizar', [TareaController::class, 'actualizar']);
$router->post('/api/tarea/eliminar', [TareaController::class, 'eliminar']);

// API para proyectos
$router->post('/api/proyecto/eliminar', [DashboradController::class, 'eliminar_proyecto']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();