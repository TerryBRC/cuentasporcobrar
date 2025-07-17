<?php
// Detalle de cliente
include __DIR__ . '/../layout/header.php';
?>
<div class="container">
    <h2>Detalle de Cliente</h2>
    <ul class="list-group">
        <li class="list-group-item"><strong>ID:</strong> <?= htmlspecialchars($cliente['cliente_id']) ?></li>
        <li class="list-group-item"><strong>Nombre:</strong> <?= htmlspecialchars($cliente['nombre']) ?></li>
        <li class="list-group-item"><strong>Identificación:</strong> <?= htmlspecialchars($cliente['identificacion']) ?></li>
        <li class="list-group-item"><strong>Dirección:</strong> <?= htmlspecialchars($cliente['direccion']) ?></li>
        <li class="list-group-item"><strong>Teléfono:</strong> <?= htmlspecialchars($cliente['telefono']) ?></li>
        <li class="list-group-item"><strong>Activo:</strong> <?= ($cliente['activo'] ?? 0) ? 'Sí' : 'No' ?></li> </ul>
    <a href="<?php echo BASE_URL; ?>/clientes" class="btn btn-secondary mt-3">Volver</a>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>