<?php
// src/views/movimientos/create.php
include __DIR__ . '/../layout/header.php';
?>
<div class="container">
    <h2>Registrar Nuevo Cargo para Cliente: <?= htmlspecialchars($cliente['nombre'] ?? 'N/A') ?></h2>
    <p class="lead">Utiliza este formulario para registrar un nuevo cargo (DEBE) a la cuenta del cliente.</p>

    <form action="<?php echo BASE_URL; ?>/clientes/<?= htmlspecialchars($cliente['cliente_id']) ?>/movimientos/crear" method="post">
        <div class="mb-3">
            <label for="numero_comprobante" class="form-label">Número de Comprobante:</label>
            <input type="text" class="form-control" id="numero_comprobante" name="numero_comprobante" placeholder="Opcional">
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción del Cargo:</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Ej: Venta de mercadería, Servicio de reparación" required></textarea>
        </div>
        <div class="mb-3">
            <label for="debe" class="form-label">Monto del Cargo (Debe C$):</label>
            <input type="number" class="form-control" id="debe" name="debe" step="0.01" min="0.01" value="0.00" required>
        </div>
        <div class="mb-3">
            <label for="haber" class="form-label">Monto del Abono (Haber C$):</label>
            <input type="number" class="form-control" id="haber" name="haber" step="0.01" value="0.00" readonly>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Cargo</button>
        <a href="<?php echo BASE_URL; ?>/clientes/<?= htmlspecialchars($cliente['cliente_id']) ?>/movimientos" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>