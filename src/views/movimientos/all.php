<?php
// src/views/movimientos/all.php
include __DIR__ . '/../layout/header.php';

// Captura las fechas actuales de la URL (GET) para rellenar el formulario de filtro
$fecha_inicio_val = $_GET['fecha_inicio'] ?? '';
$fecha_fin_val = $_GET['fecha_fin'] ?? '';

// La variable $filtros_aplicados viene del controlador
// Si $movimientos está vacío Y no hay filtros aplicados, se muestra el mensaje inicial.
// Si $movimientos está vacío PERO sí hay filtros aplicados, se muestra "No hay movimientos para este rango".
?>
<div class="container">
    <h2>Libro Mayor General de Movimientos</h2>
    <p class="lead">Listado de todos los movimientos registrados para todos los clientes.</p>

    <form method="get" class="row g-3 align-items-end mb-4">
        <div class="col-md-3">
            <label for="fecha_inicio" class="form-label">Fecha Inicio:</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio_val) ?>" required>
        </div>
        <div class="col-md-3">
            <label for="fecha_fin" class="form-label">Fecha Fin:</label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin_val) ?>" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-info w-100">Filtrar</button>
        </div>
        <?php if ($fecha_inicio_val || $fecha_fin_val): ?>
        <div class="col-md-2">
            <a href="<?php echo BASE_URL; ?>/movimientos" class="btn btn-secondary w-100">Limpiar Filtro</a>
        </div>
        <?php endif; ?>
    </form>

    <a href="<?php echo BASE_URL; ?>/clientes" class="btn btn-secondary mb-3">Volver a Clientes</a>

    <?php if ($filtros_aplicados && !empty($movimientos)): ?>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Comprobante</th>
                    <th>Descripción</th>
                    <th>Debe (C$)</th>
                    <th>Haber (C$)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movimientos as $movimiento): ?>
                    <tr>
                        <td><?= htmlspecialchars($movimiento['cliente_nombre'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($movimiento['fecha']) ?></td>
                        <td><?= htmlspecialchars($movimiento['numero_comprobante'] ?? '') ?></td>
                        <td><?= htmlspecialchars($movimiento['descripcion']) ?></td>
                        <td class="text-end"><?= number_format($movimiento['debe'], 2) ?></td>
                        <td class="text-end"><?= number_format($movimiento['haber'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <?php
                $totalDebe = array_sum(array_column($movimientos, 'debe'));
                $totalHaber = array_sum(array_column($movimientos, 'haber'));
                $saldoNetoGeneral = $totalDebe - $totalHaber;
                ?>
                <tr>
                    <th colspan="4" class="text-end">Total General Debe:</th>
                    <th class="text-end"><?= number_format($totalDebe, 2) ?></th>
                    <th></th>
                </tr>
                 <tr>
                    <th colspan="5" class="text-end">Total General Haber:</th>
                    <th class="text-end"><?= number_format($totalHaber, 2) ?></th>
                </tr>
                 <tr>
                    <th colspan="5" class="text-end">SALDO TOTAL NETO:</th>
                    <th class="text-end">
                        <span class="<?= ($saldoNetoGeneral < 0) ? 'text-danger' : 'text-success' ?>">
                            <?= number_format($saldoNetoGeneral, 2) ?>
                        </span>
                    </th>
                </tr>
            </tfoot>
        </table>
    <?php elseif ($filtros_aplicados && empty($movimientos)): ?>
        <div class="alert alert-warning mt-3" role="alert">
            No se encontraron movimientos para el rango de fechas seleccionado.
        </div>
    <?php else: ?>
        <div class="alert alert-info mt-3" role="alert">
            Por favor, selecciona un rango de fechas y haz clic en "Filtrar" para ver el Libro Mayor General de Movimientos.
        </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>