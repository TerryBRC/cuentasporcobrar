<?php
// === DEFINIR LA URL BASE DE LA APLICACIÓN ===
// Esta constante será la base para todos tus enlaces HTML.
// Se recomienda obtenerla dinámicamente si la aplicación puede moverse.
// Para tu caso en XAMPP en un subdirectorio:
define('BASE_URL', '/cuentas_x_cobrar');

// --- Configuración de la base de datos ---
// Asegúrate de que este archivo exista y configure $pdo
require_once __DIR__ . '/../config/database.php';

// === Cargar Controladores ===
require_once __DIR__ . '/../src/controllers/ClienteController.php';
require_once __DIR__ . '/../src/controllers/MovimientoController.php';

// === Instanciar Controladores ===
$clienteController = new ClienteController();
$movimientoController = new MovimientoController();

// === ENRUTADOR ===

$request_uri = $_SERVER['REQUEST_URI'];

// Eliminar BASE_URL del inicio de la URI para obtener el path relativo
$path = str_replace(BASE_URL, '', $request_uri);
$path = strtok($path, '?'); // Quitar la query string
$path = trim($path, '/'); // Quitar barras iniciales/finales

// Segmentar la URI
$segments = array_values(array_filter(explode('/', $path)));

// --- Regla para la página de inicio (/) ---
// Si la URI es solo BASE_URL o BASE_URL/, cargar el dashboard
if (empty($segments)) {
    include __DIR__ . '/../src/views/layout/dashboard.php';
    return;
}

// --- Rutas de Movimientos Globales ---
// Ruta para el "Libro Mayor General de Movimientos" (ej: /cuentas_x_cobrar/movimientos)
// Esta debe ir ANTES de las rutas de 'clientes' para que no haya conflictos.
if ($segments[0] === 'movimientos' && (!isset($segments[1]) || $segments[1] === '')) {
    $movimientoController->index(); // Llama a index sin ID de cliente (para todos los movimientos)
    return;
}

// --- Rutas de Clientes ---
if ($segments[0] === 'clientes') {
    // clientes/ (listar todos los clientes)
    if (!isset($segments[1]) || $segments[1] === '') {
        $clienteController->index();
        return;
    }

    // clientes/crear (formulario para nuevo cliente)
    if ($segments[1] === 'crear') {
        $clienteController->create();
        return;
    }

    // clientes/{id} (ver detalles de un cliente)
    if (isset($segments[1]) && is_numeric($segments[1]) && !isset($segments[2])) {
        $clienteController->show($segments[1]);
        return;
    }

    // clientes/editar/{id} (formulario para editar cliente)
    if ($segments[1] === 'editar' && isset($segments[2]) && is_numeric($segments[2])) {
        $clienteController->edit($segments[2]);
        return;
    }

    // clientes/eliminar/{id} (acción de eliminar cliente)
    if ($segments[1] === 'eliminar' && isset($segments[2]) && is_numeric($segments[2])) {
        $clienteController->delete($segments[2]);
        return;
    }

    // --- Rutas Anidadas de Movimientos para Clientes ---
    // clientes/{id}/movimientos (listar movimientos de un cliente específico)
    if (isset($segments[1]) && is_numeric($segments[1]) && isset($segments[2]) && $segments[2] === 'movimientos' && !isset($segments[3])) {
        $movimientoController->index($segments[1]); // Llama a index CON el ID de cliente
        return;
    }

    // clientes/{id}/movimientos/crear (formulario para nuevo movimiento de un cliente)
    if (isset($segments[1]) && is_numeric($segments[1]) && isset($segments[2]) && $segments[2] === 'movimientos' && isset($segments[3]) && $segments[3] === 'crear') {
        $movimientoController->create($segments[1]);
        return;
    }

    // Opcional: Rutas para editar/eliminar movimientos si las implementas (descomentar si las usas)
    /*
    // clientes/{id}/movimientos/editar/{movimiento_id}
    if (isset($segments[1]) && is_numeric($segments[1]) && $segments[2] === 'movimientos' && $segments[3] === 'editar' && isset($segments[4]) && is_numeric($segments[4])) {
        $movimientoController->edit($segments[4]); // Pasar movimiento_id
        return;
    }
    // clientes/{id}/movimientos/eliminar/{movimiento_id}
    if (isset($segments[1]) && is_numeric($segments[1]) && $segments[2] === 'movimientos' && $segments[3] === 'eliminar' && isset($segments[4]) && is_numeric($segments[4])) {
        $movimientoController->delete($segments[4]); // Pasar movimiento_id
        return;
    }
    */
}

// Si ninguna ruta coincide, mostrar un error 404
http_response_code(404);
include __DIR__ . '/../src/views/error404.php';

?>