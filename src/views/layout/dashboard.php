<?php
require_once __DIR__ . '/header.php';
?>
<div class="container">
    <h1 class="mt-4 mb-4">Bienvenido a Electro Hogar - Cuentas por Cobrar</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Clientes</div>
                <div class="card-body">
                    <p>Gestión de clientes: alta, edición, eliminación y consulta.</p>
                    <a href="<?php echo BASE_URL; ?>/clientes" class="btn btn-primary">Ir a Clientes</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">Movimientos</div>
                <div class="card-body">
                    <p>Registro de movimientos de cuentas por cobrar, abonos y comprobantes.</p>
                    <a href="<?php echo BASE_URL; ?>/movimientos" class="btn btn-primary">Ir a Movimientos</a>
                </div>
            </div>
        </div>
    </div>

</div>
<?php
require_once __DIR__ . '/footer.php';
?>