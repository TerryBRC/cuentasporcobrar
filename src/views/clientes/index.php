<?php
// src/views/clientes/index.php
include __DIR__ . '/../layout/header.php';
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