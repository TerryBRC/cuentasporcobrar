<?php
// Formulario para editar cliente
include __DIR__ . '/../layout/header.php';
?>
<div class="container">
    <h2>Editar Cliente</h2>
    <form method="post">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="identificacion" class="form-label">Identificación</label>
            <input type="text" name="identificacion" id="identificacion" class="form-control" value="<?= htmlspecialchars($cliente['identificacion']) ?? '' ?>" required>
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" name="direccion" id="direccion" class="form-control" value="<?= htmlspecialchars($cliente['direccion']) ?? '' ?>" required>
        </div>

        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="tel" name="telefono" id="telefono" class="form-control"
                   value="<?= htmlspecialchars($cliente['telefono']) ?? '' ?>" required
                   pattern="[0-9]*"    inputmode="numeric" title="Por favor, ingresa solo números (8 dígitos para números locales)."
                   maxlength="20"      placeholder="Ej: 88888888 o un número internacional">
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1" <?= ($cliente['activo'] ?? 0) ? 'checked' : '' ?>>
            <label class="form-check-label" for="activo">Activo</label>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="<?php echo BASE_URL; ?>/clientes" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const telefonoInput = document.getElementById('telefono');

    telefonoInput.addEventListener('input', function(event) {
        // Elimina cualquier caracter que NO sea un dígito
        this.value = this.value.replace(/[^0-9]/g, '');

        // Opcional: Si quieres un formato estricto de 8 dígitos para Nicaragua:
        // if (this.value.length > 8) {
        //     this.value = this.value.substring(0, 8);
        // }
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>