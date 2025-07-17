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
        <div class="mb-3">
            <label for="identificacion" class="form-label">Identificación</label>
            <input type="text" name="identificacion" id="identificacion" class="form-control">
        </div>
        <div class="mb-3">
            <label for="frecuencia" class="form-label">Frecuencia de Pago</label>
            <select name="frecuencia" id="frecuencia" class="form-select">
                <option value="">Seleccione una opción</option>
                <option value="Mensual">Mensual</option>
                <option value="Quincenal">Quincenal</option>
                <option value="Semanal">Semanal</option>
            </select>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" name="direccion" id="direccion" class="form-control">
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="tel" name="telefono" id="telefono" class="form-control"
                   placeholder="Ej: 8888-8888 o +1 (555) 123-4567 ext 123"
                   required
                   pattern="[0-9]*"    inputmode="numeric" maxlength="20"      title="Por favor, ingresa solo números.">
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="<?php echo BASE_URL; ?>/clientes" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const telefonoInput = document.getElementById('telefono');

    telefonoInput.addEventListener('input', function(event) {
        // Elimina cualquier caracter que NO sea un dígito
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>