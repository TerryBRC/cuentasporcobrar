<?php
// src/views/error404.php

// Incluye el header para mantener la consistencia de la interfaz
// Asegúrate de que BASE_URL esté definido en tu public/index.php
// antes de incluir este archivo.
include __DIR__ . '/layout/header.php';
?>

<div class="container text-center py-5">
    <h1 class="display-4">404</h1>
    <p class="lead">Lo sentimos, la página que buscas no pudo ser encontrada.</p>
    <p>Parece que te has perdido en el espacio de las Cuentas por Cobrar.</p>
    <a href="<?php echo BASE_URL; ?>/" class="btn btn-primary mt-3">Volver al Dashboard</a>
</div>

<?php
// Incluye el footer
include __DIR__ . '/layout/footer.php';
?>