<?php
// src/models/Cliente.php
require_once __DIR__ . '/../../config/database.php';

class Cliente {
    public static function getAll() {
        global $pdo;
        // Si solo quieres mostrar clientes activos por defecto, modifica esta consulta:
        // $stmt = $pdo->query('SELECT cliente_id, nombre, identificacion, direccion, telefono, activo FROM clientes WHERE activo = 1 ORDER BY nombre');
        // O si quieres mostrarlos todos y gestionar la visibilidad en la vista:
        $stmt = $pdo->query('SELECT cliente_id, nombre, identificacion, direccion, telefono, frecuencia_pago, activo FROM clientes ORDER BY nombre');
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT cliente_id, nombre, identificacion, direccion, telefono, activo FROM clientes WHERE cliente_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($nombre, $identificacion, $frecuencia_pago, $direccion, $telefono) {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO clientes (nombre, identificacion, frecuencia_pago, direccion, telefono, activo) VALUES (?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$nombre, $identificacion, $frecuencia_pago, $direccion, $telefono, 1]); // Por defecto, 'activo' es 1 (true)
    }

    public static function update($id, $nombre, $identificacion, $direccion, $telefono, $activo) {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE clientes SET nombre = ?, identificacion = ?, direccion = ?, telefono = ?, activo = ? WHERE cliente_id = ?');
        return $stmt->execute([$nombre, $identificacion, $direccion, $telefono, $activo, $id]);
    }

    // *** CAMBIO AQUÍ: El método delete ahora alternará el estado 'activo' ***
    public static function delete($id) {
        global $pdo;

        // 1. Obtener el estado actual del cliente
        $stmt = $pdo->prepare('SELECT activo FROM clientes WHERE cliente_id = ?');
        $stmt->execute([$id]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch como array asociativo

        if ($cliente) {
            // 2. Determinar el nuevo estado (opuesto al actual)
            $nuevo_estado = ($cliente['activo'] == 1) ? 0 : 1; // Si es 1, lo cambia a 0; si es 0, lo cambia a 1

            // 3. Actualizar el campo 'activo'
            $update_stmt = $pdo->prepare('UPDATE clientes SET activo = ? WHERE cliente_id = ?');
            return $update_stmt->execute([$nuevo_estado, $id]);
        }
        return false; // Cliente no encontrado
    }
}