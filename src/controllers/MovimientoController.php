<?php
// src/controllers/MovimientoController.php
require_once __DIR__ . '/../models/Movimiento.php';
require_once __DIR__ . '/../models/Cliente.php'; // Necesario para obtener el nombre del cliente

class MovimientoController {
    /**
     * Muestra los movimientos de un cliente específico, o todos los movimientos si no se provee un ID de cliente.
     * Permite filtrar por rango de fechas.
     *
     * @param int|null $cliente_id El ID del cliente para filtrar movimientos, o null para todos los movimientos.
     */
    public function index($cliente_id = null) {
        // Obtener filtros de fecha de la URL (GET request)
        $fecha_inicio = $_GET['fecha_inicio'] ?? null;
        $fecha_fin = $_GET['fecha_fin'] ?? null;

        // Limpiar y validar fechas básicas (opcional, pero recomendado para producción)
        if ($fecha_inicio && !preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha_inicio)) {
            $fecha_inicio = null; // Invalidar si el formato no es YYYY-MM-DD
        }
        if ($fecha_fin && !preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha_fin)) {
            $fecha_fin = null; // Invalidar si el formato no es YYYY-MM-DD
        }

        $cliente = null;
        $movimientos = [];
        $filtros_aplicados = false; // <<< INICIALIZAR AQUÍ

        if ($cliente_id !== null) {
            // --- Lógica para MOVIMIENTOS DE UN CLIENTE ESPECÍFICO (clientes/{id}/movimientos) ---
            $cliente = Cliente::getById($cliente_id);
            if (!$cliente) {
                header('Location: ' . BASE_URL . '/clientes'); // Cliente no existe, redirigir
                exit;
            }
            $movimientos = Movimiento::getByClientId($cliente_id, $fecha_inicio, $fecha_fin);
            // En esta vista (index.php) no usas $filtros_aplicados, pero se puede definir por consistencia
            $filtros_aplicados = ($fecha_inicio && $fecha_fin); // Para index.php, aunque no se use en tu template actual
            include __DIR__ . '/../views/movimientos/index.php'; // Vista de movimientos de cliente
        } else {
            // --- Lógica para el LIBRO MAYOR GENERAL (ruta /movimientos) ---

            // Definir $filtros_aplicados antes de cualquier condición que pueda no ejecutar la asignación
            $filtros_aplicados = ($fecha_inicio && $fecha_fin); // <<< ASIGNAR VALOR AQUÍ

            if ($filtros_aplicados) { // Solo cargar movimientos si hay fechas
                $movimientos = Movimiento::getAllMovimientos($fecha_inicio, $fecha_fin);
            } else {
                $movimientos = []; // Asegura que $movimientos siempre sea un array incluso si no hay filtros
            }

            include __DIR__ . '/../views/movimientos/all.php'; // Vista general de movimientos
        }
    }

    /**
     * Muestra el formulario para crear un nuevo movimiento, o procesa el envío de dicho formulario.
     * Puede manejar tanto cargos (Debe) como pagos (Haber).
     *
     * @param int $cliente_id El ID del cliente para el cual se creará el movimiento.
     */
    public function create($cliente_id) {
        $cliente = Cliente::getById($cliente_id);
        if (!$cliente) {
            header('Location: ' . BASE_URL . '/clientes');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fecha = $_POST['fecha'] ?? '';
            $numero_comprobante = $_POST['numero_comprobante'] ?? null;
            $descripcion = $_POST['descripcion'] ?? '';

            // Determine if it's a payment (Haber) or a charge (Debe)
            $is_payment = isset($_POST['is_payment']) && $_POST['is_payment'] == '1';

            $debe = 0.00;
            $haber = 0.00;

            if ($is_payment) {
                // If it's a payment, only 'haber' should have a value
                $haber = (float) str_replace(',', '', $_POST['haber'] ?? 0.00);
                // Ensure description makes sense for a payment
                if (empty($descripcion)) {
                    $descripcion = "Pago de cliente";
                }
            } else {
                // Otherwise, it's a charge, only 'debe' should have a value
                $debe = (float) str_replace(',', '', $_POST['debe'] ?? 0.00);
            }


            // Validation: Ensure description is not empty and at least one value (Debe or Haber) is positive
            if (!empty($descripcion) && ($debe > 0 || $haber > 0)) {
                Movimiento::create($cliente_id, $fecha, $numero_comprobante, $descripcion, $debe, $haber);
                header('Location: ' . BASE_URL . '/clientes/' . $cliente_id . '/movimientos');
                exit;
            } else {
                // Error message for invalid input
                $error_message = "Por favor, completa la descripción y al menos un valor (Debe o Haber) mayor a cero.";
                // You might want to pass this error back to the view
                // For simplicity, we'll just echo it for now.
                echo "<div class='alert alert-danger'>{$error_message}</div>";
            }
        }
        // If not a POST request, or validation failed, show the form for creating a new 'Debe' movement
        // (The 'Registrar Cargo' button directs here)
        include __DIR__ . '/../views/movimientos/create.php';
    }

    // --- Métodos Opcionales (Descomentar y usar si se implementan en el modelo y rutas) ---
    /*
    public function edit($movimiento_id) {
        $movimiento = Movimiento::getById($movimiento_id);
        if (!$movimiento) {
            header('Location: ' . BASE_URL . '/clientes'); // O a la lista de movimientos del cliente
            exit;
        }
        $cliente = Cliente::getById($movimiento['cliente_id']); // Obtener datos del cliente asociado

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fecha = $_POST['fecha'] ?? '';
            $numero_comprobante = $_POST['numero_comprobante'] ?? null;
            $descripcion = $_POST['descripcion'] ?? '';
            $debe = $_POST['debe'] ?? 0.00;
            $haber = $_POST['haber'] ?? 0.00;

            $debe = (float) str_replace(',', '', $debe);
            $haber = (float) str_replace(',', '', $haber);

            if (!empty($descripcion) && ($debe > 0 || $haber > 0)) {
                Movimiento::update($movimiento_id, $fecha, $numero_comprobante, $descripcion, $debe, $haber);
                header('Location: ' . BASE_URL . '/clientes/' . $cliente['cliente_id'] . '/movimientos');
                exit;
            } else {
                echo "<div class='alert alert-danger'>Por favor, completa la descripción y al menos un valor (Debe o Haber) mayor a cero.</div>";
            }
        }
        include __DIR__ . '/../views/movimientos/edit.php';
    }

    public function delete($movimiento_id) {
        $movimiento = Movimiento::getById($movimiento_id);
        if ($movimiento) {
            Movimiento::delete($movimiento_id);
            header('Location: ' . BASE_URL . '/clientes/' . $movimiento['cliente_id'] . '/movimientos');
            exit;
        }
        header('Location: ' . BASE_URL . '/clientes'); // Fallback si el movimiento no se encuentra
        exit;
    }
    */
}