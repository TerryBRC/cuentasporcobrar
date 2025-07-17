<?php
// src/views/movimientos/index.php
include __DIR__ . '/../layout/header.php';

// Captura las fechas actuales de la URL (GET) para rellenar el formulario de filtro
$fecha_inicio_val = $_GET['fecha_inicio'] ?? '';
$fecha_fin_val = $_GET['fecha_fin'] ?? '';

// Calcular el saldo actual del cliente para el período visible/total
// Se calcula aquí para la tarjeta principal de saldo y el pie de tabla.
$saldo_periodo_visible = 0;
foreach ($movimientos as $movimiento) {
    $saldo_periodo_visible += ($movimiento['debe'] - $movimiento['haber']);
}

// NO se usa $saldo_es_cero_o_negativo ni $error_message en esta versión,
// ya que el usuario pidió solo el cambio a columnas por ahora.

?>
<div class="container">
    <h2>Movimientos del Cliente: <?= htmlspecialchars($cliente['nombre'] ?? 'Desconocido') ?></h2>
    <p>Identificación: <?= htmlspecialchars($cliente['identificacion'] ?? '') ?></p>
    <p>Teléfono: <?= htmlspecialchars($cliente['telefono'] ?? '') ?></p>

    <hr>

    <div class="row">
        <div class="col-md-5">
            <div class="card bg-light mb-4">
                <div class="card-body">
                    <h4 class="card-title">Saldo Actual:
                        <span class="<?= ($saldo_periodo_visible < 0) ? 'text-danger' : 'text-success' ?>">
                            <?= number_format($saldo_periodo_visible, 2) ?> C$
                        </span>
                    </h4>
                    <?php if (!$fecha_inicio_val && !$fecha_fin_val): ?>
                        <p class="card-text text-muted">Este es el saldo acumulado de todos los movimientos registrados para este cliente.</p>
                    <?php else: ?>
                        <p class="card-text text-muted">Este es el saldo final para el rango de fechas seleccionado.</p>
                        <p class="card-text text-muted">Para ver el saldo total actual del cliente, <a href="<?php echo BASE_URL; ?>/clientes/<?= htmlspecialchars($cliente['cliente_id']) ?>/movimientos">limpie los filtros de fecha</a>.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    Registrar Nuevo Pago (Haber)
                </div>
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>/clientes/<?= htmlspecialchars($cliente['cliente_id']) ?>/movimientos/crear" method="post">
                        <input type="hidden" name="is_payment" value="1">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="fecha_pago" class="form-label">Fecha del Pago:</label>
                                <input type="date" class="form-control" id="fecha_pago" name="fecha" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="monto_pago" class="form-label">Monto del Pago (C$):</label>
                                <input type="number" class="form-control" id="monto_pago" name="haber" step="0.01" min="0.01" required>
                            </div>
                            <div class="col-md-4"> <label for="numero_comprobante_pago" class="form-label">No. Comprobante:</label>
                                <input type="text" class="form-control" id="numero_comprobante_pago" name="numero_comprobante" placeholder="Opcional">
                            </div>
                            <div class="col-12"> <label for="descripcion_pago" class="form-label">Descripción del Pago:</label>
                                <textarea class="form-control" id="descripcion_pago" name="descripcion" rows="2" placeholder="Ej: Pago de factura #123, Abono quincenal" required>Pago de cliente</textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">Registrar Pago</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <a href="<?php echo BASE_URL; ?>/clientes/<?= htmlspecialchars($cliente['cliente_id']) ?>/movimientos/crear" class="btn btn-primary mb-3 w-100">Registrar Nuevo Cargo (Debe)</a>
            <a href="<?php echo BASE_URL; ?>/clientes" class="btn btn-secondary w-100">Volver a Clientes</a>
        </div>

        <div class="col-md-7">
            <form method="get" class="row g-3 align-items-end mb-4">
                <div class="col-md-4">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio:</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio_val) ?>">
                </div>
                <div class="col-md-4">
                    <label for="fecha_fin" class="form-label">Fecha Fin:</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin_val) ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-info me-2">Filtrar</button>
                    <?php if ($fecha_inicio_val || $fecha_fin_val): ?>
                        <a href="<?php echo BASE_URL; ?>/clientes/<?= htmlspecialchars($cliente['cliente_id']) ?>/movimientos" class="btn btn-secondary">Limpiar</a>
                    <?php endif; ?>
                </div>
            </form>

            <table class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Comprobante</th>
                        <th>Descripción</th>
                        <th>Debe (C$)</th>
                        <th>Haber (C$)</th>
                        <th>SALDO (C$)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($movimientos)): ?>
                        <?php $saldo_actual_linea = 0; // Se usa para el saldo acumulado en cada fila de la tabla 
                        ?>
                        <?php foreach ($movimientos as $movimiento): ?>
                            <tr>
                                <td><?= htmlspecialchars($movimiento['fecha']) ?></td>
                                <td><?= htmlspecialchars($movimiento['numero_comprobante'] ?? '') ?></td>
                                <td><?= htmlspecialchars($movimiento['descripcion']) ?></td>
                                <td class="text-end"><?= number_format($movimiento['debe'], 2) ?></td>
                                <td class="text-end"><?= number_format($movimiento['haber'], 2) ?></td>
                                <?php
                                // Calcula el saldo: Debe suma, Haber resta
                                $saldo_actual_linea += ($movimiento['debe'] - $movimiento['haber']);
                                ?>
                                <td class="text-end">
                                    <span class="<?= ($saldo_actual_linea < 0) ? 'text-danger' : 'text-success' ?>">
                                        <?= number_format($saldo_actual_linea, 2) ?>
                                    </span>
                                </td>
                                <td>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay movimientos registrados para este cliente en el rango de fechas seleccionado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Saldo Final del Período:</th>
                        <th class="text-end">
                            <span class="<?= ($saldo_periodo_visible < 0) ? 'text-danger' : 'text-success' ?>">
                                <?= number_format($saldo_periodo_visible, 2) ?>
                            </span>
                        </th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>