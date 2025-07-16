
<?php
// Enrutador para URLs amigables
$uri = $_SERVER['REQUEST_URI'];
$base = '/cuentas_x_cobrar/public';
$path = preg_replace('#^' . preg_quote($base, '#') . '#', '', $uri);
$path = strtok($path, '?'); // Quita query string
$segments = array_values(array_filter(explode('/', $path)));

if (empty($segments)) {
    // Dashboard principal
    include __DIR__ . '/../src/views/layout/dashboard.php';
    return;
}

// Clientes
if ($segments[0] === 'clientes') {
    require_once __DIR__ . '/../src/controllers/ClienteController.php';
    $clienteController = new ClienteController();
    // /clientes
    if (count($segments) === 1) {
        $clienteController->index();
        return;
    }
    // /clientes/crear
    if ($segments[1] === 'crear') {
        $clienteController->create();
        return;
    }
    // /clientes/editar/{id}
    if ($segments[1] === 'editar' && isset($segments[2])) {
        $clienteController->edit($segments[2]);
        return;
    }
    // /clientes/eliminar/{id}
    if ($segments[1] === 'eliminar' && isset($segments[2])) {
        $clienteController->delete($segments[2]);
        return;
    }
    // /clientes/{id}
    if (is_numeric($segments[1])) {
        $clienteController->show($segments[1]);
        return;
    }
}

// 404
http_response_code(404);
include __DIR__ . '/../src/views/error404.php';
?>