<?php
// src/views/clientes/index.php
include __DIR__ . '/../layout/header.php';
// Cargamos el controlador de movimientos
require_once __DIR__ . '/../../controllers/MovimientoController.php';
//Verificamos los ultimos movimientos de clientes
$idsClientes = [];
if (isset($clientes) && is_array($clientes)) {
    foreach ($clientes as $cliente) {
        $idsClientes[] = $cliente['cliente_id'];
    }
}
//verficamos los ultimos movimientos de clientes en la tabla de movimientos
$ultimosMovimientos = [];
if (!empty($idsClientes)) {
    $movimientoController = new MovimientoController();
    $ultimosMovimientos = $movimientoController->getUltimosMovimientosPorClientes($idsClientes);
}

?>
<div class="container">
    <h2>Listado de Clientes</h2>
    <p class="lead">Aquí puedes ver y gestionar la información de tus clientes.</p>
    <a href="<?php echo BASE_URL; ?>/clientes/crear" class="btn btn-primary mb-3">Nuevo Cliente</a>

    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Identificación</th>
                <th>Teléfono</th>
                <th>Frecuencia</th>
                <th>Estado</th>
                <th>Acciones</th> </tr>
        </thead>
        <tbody>
            <?php if (!empty($clientes)): ?>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= htmlspecialchars($cliente['cliente_id']) ?></td>
                        <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                        <td><?= htmlspecialchars($cliente['identificacion']) ?></td>
                        <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                        <td>
                            <?php
                            // Calcular la frecuencia de pagos
                            $frecuencia = '';
                            if ($cliente['frecuencia_pago'] == 'Mensual') {
                                $frecuencia = 'Mensual';
                            } elseif ($cliente['frecuencia_pago'] == 'Quincenal') {
                                $frecuencia = 'Quincenal';
                            } elseif ($cliente['frecuencia_pago'] == 'Semanal') {
                                $frecuencia = 'Semanal';
                            } else {
                                $frecuencia = 'No especificada';
                            }
                            echo htmlspecialchars($frecuencia);
                            ?>
                        </td>
                        <td>
                            <?= $cliente['activo'] ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>' ?>
                        </td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/clientes/<?= htmlspecialchars($cliente['cliente_id']) ?>/movimientos" class="btn btn-info btn-sm me-2">
                                <i class="bi bi-wallet-fill"></i> Ver Movimientos
                            </a>
                            <a href="<?php echo BASE_URL; ?>/clientes/editar/<?= htmlspecialchars($cliente['cliente_id']) ?>" class="btn btn-warning btn-sm me-2">
                                <i class="bi bi-pencil-fill"></i> Editar
                            </a>
                            <a href="<?php echo BASE_URL; ?>/clientes/eliminar/<?= htmlspecialchars($cliente['cliente_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas cambiar el estado de este cliente?');">
                                <i class="bi bi-trash-fill"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No hay clientes registrados en el sistema.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>