<?php
// src/models/Movimiento.php
require_once __DIR__ . '/../../config/database.php';

class Movimiento {
    /**
     * Obtiene todos los movimientos para un cliente específico, opcionalmente filtrados por rango de fechas.
     * @param int $cliente_id El ID del cliente.
     * @param string|null $fecha_inicio Fecha de inicio del filtro (YYYY-MM-DD).
     * @param string|null $fecha_fin Fecha de fin del filtro (YYYY-MM-DD).
     * @return array Lista de movimientos.
     */
    public static function getByClientId($cliente_id, $fecha_inicio = null, $fecha_fin = null) {
        global $pdo;
        $sql = 'SELECT * FROM movimientos_cliente WHERE cliente_id = ?';
        $params = [$cliente_id];

        if ($fecha_inicio) {
            $sql .= ' AND fecha >= ?';
            $params[] = $fecha_inicio;
        }
        if ($fecha_fin) {
            $sql .= ' AND fecha <= ?';
            $params[] = $fecha_fin;
        }
        // CRUCIAL: Ordenar por fecha y luego por ID en ASC para el cálculo de saldo acumulado
        $sql .= ' ORDER BY fecha ASC, movimiento_id ASC'; 

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo movimiento en la base de datos.
     * @param int $cliente_id El ID del cliente asociado.
     * @param string $fecha La fecha del movimiento (YYYY-MM-DD).
     * @param string|null $numero_comprobante Número de comprobante (opcional).
     * @param string $descripcion Descripción del movimiento.
     * @param float $debe Cantidad en el "Debe".
     * @param float $haber Cantidad en el "Haber".
     * @return bool True si la operación fue exitosa, false en caso contrario.
     */
    public static function create($cliente_id, $numero_comprobante, $descripcion, $debe, $haber) {
        global $pdo;
        $stmt = $pdo->prepare(
            'INSERT INTO movimientos_cliente (cliente_id, numero_comprobante, descripcion, debe, haber)
             VALUES (?, ?, ?, ?, ?)'
        );
        return $stmt->execute([$cliente_id, $numero_comprobante, $descripcion, $debe, $haber]);
    }

    /**
     * Obtiene todos los movimientos de todos los clientes, opcionalmente filtrados por rango de fechas.
     * Incluye el nombre del cliente.
     * @param string|null $fecha_inicio Fecha de inicio del filtro (YYYY-MM-DD).
     * @param string|null $fecha_fin Fecha de fin del filtro (YYYY-MM-DD).
     * @return array Lista de todos los movimientos con nombre de cliente.
     */
    public static function getAllMovimientos($fecha_inicio = null, $fecha_fin = null) {
        global $pdo;
        $sql = 'SELECT mc.*, c.nombre AS cliente_nombre
                FROM movimientos_cliente mc
                JOIN clientes c ON mc.cliente_id = c.cliente_id';
        $params = [];
        $where_clauses = [];

        if ($fecha_inicio) {
            $where_clauses[] = 'mc.fecha >= ?';
            $params[] = $fecha_inicio;
        }
        if ($fecha_fin) {
            $where_clauses[] = 'mc.fecha <= ?';
            $params[] = $fecha_fin;
        }

        if (!empty($where_clauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $where_clauses);
        }
        // CRUCIAL: Ordenar por fecha y luego por ID en ASC
        $sql .= ' ORDER BY mc.fecha ASC, mc.movimiento_id ASC'; 

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- Métodos Opcionales (mantener si planeas implementarlos) ---
    /**
     * Obtiene un movimiento específico por su ID.
     * @param int $movimiento_id El ID del movimiento.
     * @return array|false El movimiento encontrado o false si no existe.
     */
    public static function getById($movimiento_id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM movimientos_cliente WHERE movimiento_id = ?');
        $stmt->execute([$movimiento_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza un movimiento existente.
     * @param int $movimiento_id ID del movimiento a actualizar.
     * @param string $fecha Nueva fecha.
     * @param string|null $numero_comprobante Nuevo número de comprobante.
     * @param string $descripcion Nueva descripción.
     * @param float $debe Nuevo valor en el "Debe".
     * @param float $haber Nuevo valor en el "Haber".
     * @return bool True si la operación fue exitosa, false en caso contrario.
     */
    public static function update($movimiento_id, $numero_comprobante, $descripcion, $debe, $haber) {
        global $pdo;
        $stmt = $pdo->prepare(
            'UPDATE movimientos_cliente SET numero_comprobante = ?, descripcion = ?, debe = ?, haber = ?
             WHERE movimiento_id = ?'
        );
        return $stmt->execute([$numero_comprobante, $descripcion, $debe, $haber, $movimiento_id]);
    }

    /**
     * Elimina físicamente un movimiento de la base de datos.
     * ¡Cuidado al usar este método, ya que borra el registro permanentemente!
     * @param int $movimiento_id ID del movimiento a eliminar.
     * @return bool True si la operación fue exitosa, false en caso contrario.
     */
    public static function delete($movimiento_id) {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM movimientos_cliente WHERE movimiento_id = ?');
        return $stmt->execute([$movimiento_id]);
    }
}