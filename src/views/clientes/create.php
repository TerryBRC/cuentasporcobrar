<?php
// Formulario para crear cliente
include __DIR__ . '/../layout/header.php';
?>
<div class="container">
    <h2>Nuevo Cliente</h2>
    <form method="post">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="/cuentas_x_cobrar/public/clientes" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
