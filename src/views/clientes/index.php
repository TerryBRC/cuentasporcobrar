<?php
// src/views/clientes/index.php
include __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../../controllers/MovimientoController.php';

// Recolectar IDs de clientes
$idsClientes = [];
if (isset($clientes) && is_array($clientes)) {
    foreach ($clientes as $cliente) {
        $idsClientes[] = $cliente['cliente_id'];
    }
}

// Obtener últimos movimientos
$ultimosMovimientos = [];
if (!empty($idsClientes)) {
    $movimientoController = new MovimientoController();
    $ultimosMovimientos = $movimientoController->getUltimosMovimientosPorClientes($idsClientes);
}

$currentDate = new DateTime();
?>

<div class="container">
    <h2>Listado de Clientes</h2>
    <p class="lead">Aquí puedes ver y gestionar la información de tus clientes.</p>
    <a href="<?php echo BASE_URL; ?>/clientes/crear" class="btn btn-primary mb-3">Nuevo Cliente</a>

    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <!-- Tabla de clientes -->
    <!-- ocupara todo el ancho de la pantalla -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped wd-100">
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Nombre</th>
                    <th>Identificación</th>
                    <th>Teléfono</th>
                    <th>Frecuencia</th>
                    <th>Último Movimiento</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($clientes)): ?>
                    <?php foreach ($clientes as $cliente): ?>
                        <?php
                        $rowClass = '';
                        $lastMovementDate = null;
                        $daysSinceLastMovement = null;
                        $saldo = 0;

                        if (isset($ultimosMovimientos[$cliente['cliente_id']])) {
                            $mov = $ultimosMovimientos[$cliente['cliente_id']];
                            $saldo = $mov['saldo'] ?? 0;

                            if (!empty($mov['fecha'])) {
                                try {
                                    $lastMovementDate = new DateTime($mov['fecha']);
                                    $interval = $currentDate->diff($lastMovementDate);
                                    $daysSinceLastMovement = $interval->days;
                                } catch (Exception $e) {
                                    $daysSinceLastMovement = null;
                                }
                            }
                        }

                        // Colorear fila solo si tiene saldo ≠ 0
                        if ($saldo != 0 && $daysSinceLastMovement !== null) {
                            switch ($cliente['frecuencia_pago']) {
                                case 'Semanal':
                                    if ($daysSinceLastMovement >= 15 && $daysSinceLastMovement < 20) {
                                        $rowClass = 'table-warning';
                                    } elseif ($daysSinceLastMovement >= 20) {
                                        $rowClass = 'table-danger';
                                    }
                                    break;
                                case 'Quincenal':
                                    if ($daysSinceLastMovement >= 31 && $daysSinceLastMovement < 45) {
                                        $rowClass = 'table-warning';
                                    } elseif ($daysSinceLastMovement >= 45) {
                                        $rowClass = 'table-danger';
                                    }
                                    break;
                                case 'Mensual':
                                    if ($daysSinceLastMovement >= 61 && $daysSinceLastMovement < 90) {
                                        $rowClass = 'table-warning';
                                    } elseif ($daysSinceLastMovement >= 90) {
                                        $rowClass = 'table-danger';
                                    }
                                    break;
                            }
                        } elseif ($saldo != 0 && $daysSinceLastMovement === null) {
                            $rowClass = 'table-secondary';
                        }
                        ?>
                        <tr class="<?= htmlspecialchars($rowClass) ?>">
                            <!-- <td><= htmlspecialchars($cliente['cliente_id']) ?></td> -->
                            <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                            <td><?= htmlspecialchars($cliente['identificacion']) ?></td>
                            <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                            <td><?= htmlspecialchars($cliente['frecuencia_pago'] ?? 'No especificada') ?></td>
                            <td>
                                <?= $lastMovementDate ? htmlspecialchars($lastMovementDate->format('d/m/Y')) : 'Sin movimientos' ?>
                                <?php if ($rowClass === 'table-warning'): ?>
                                    <i class="bi bi-exclamation-triangle-fill text-warning" title="Posible retraso de pago"></i>
                                <?php elseif ($rowClass === 'table-danger'): ?>
                                    <i class="bi bi-exclamation-octagon-fill text-danger" title="Pago muy retrasado"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $cliente['activo'] ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>' ?>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>/clientes/<?= htmlspecialchars($cliente['cliente_id']) ?>/movimientos" class="btn btn-info btn-sm me-2">
                                    Movimientos
                                </a>
                                <a href="<?= BASE_URL ?>/clientes/editar/<?= htmlspecialchars($cliente['cliente_id']) ?>" class="btn btn-warning btn-sm me-2">
                                    Editar
                                </a>
                                <a href="<?= BASE_URL ?>/clientes/eliminar/<?= htmlspecialchars($cliente['cliente_id']) ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('¿Estás seguro de que deseas cambiar el estado de este cliente?');">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No hay clientes registrados en el sistema.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>