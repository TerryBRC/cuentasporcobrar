<?php
// Detalle de cliente
include __DIR__ . '/../layout/header.php';
?>
<div class="container">
    <h2>Detalle de Cliente</h2>
    <ul class="list-group">
        <li class="list-group-item"><strong>ID:</strong> <?= htmlspecialchars($cliente['cliente_id']) ?></li>
        <li class="list-group-item"><strong>Nombre:</strong> <?= htmlspecialchars($cliente['nombre']) ?></li>
    </ul>
    <a href="/cuentas_x_cobrar/public/clientes" class="btn btn-secondary mt-3">Volver</a>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
