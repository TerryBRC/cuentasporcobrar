<?php
require_once __DIR__ . '/footer.php';
?>
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
                    <a href="/cuentas_x_cobrar/public/clientes" class="btn btn-primary">Ir a Clientes</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">Movimientos</div>
                <div class="card-body">
                    <p>Registro de movimientos de cuentas por cobrar, abonos y comprobantes.</p>
                    <a href="#" class="btn btn-success disabled">Próximamente</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">Reportes</div>
                <div class="card-body">
                    <ul>
                        <li>Libro mayor por cliente</li>
                        <li>Reporte mensual</li>
                        <li>Balance general</li>
                    </ul>
                    <a href="#" class="btn btn-info disabled">Próximamente</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-warning text-white">Configuración</div>
                <div class="card-body">
                    <p>Opciones de configuración y administración del sistema.</p>
                    <a href="#" class="btn btn-warning disabled">Próximamente</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">Ayuda</div>
                <div class="card-body">
                    <p>Documentación y soporte para usuarios.</p>
                    <a href="#" class="btn btn-secondary disabled">Próximamente</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/footer.php';
?>