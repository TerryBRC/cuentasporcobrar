<?php
// Listado de clientes
include __DIR__ . '/../layout/header.php';
?>
<div class="container">
    <h2>Clientes</h2>
    <a href="/cuentas_x_cobrar/public/clientes/crear" class="btn btn-primary">Nuevo Cliente</a>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?= htmlspecialchars($cliente['cliente_id']) ?></td>
                <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                <td>
                    <a href="/cuentas_x_cobrar/public/clientes/<?= $cliente['cliente_id'] ?>" class="btn btn-info btn-sm">Ver</a>
                    <a href="/cuentas_x_cobrar/public/clientes/editar/<?= $cliente['cliente_id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="/cuentas_x_cobrar/public/clientes/eliminar/<?= $cliente['cliente_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que desea eliminar este cliente?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
