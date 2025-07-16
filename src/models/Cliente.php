<?php
// src/models/Cliente.php
require_once __DIR__ . '/../../config/database.php';

class Cliente {
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM clientes ORDER BY nombre');
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM clientes WHERE cliente_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($nombre) {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO clientes (nombre) VALUES (?)');
        return $stmt->execute([$nombre]);
    }

    public static function update($id, $nombre) {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE clientes SET nombre = ? WHERE cliente_id = ?');
        return $stmt->execute([$nombre, $id]);
    }

    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM clientes WHERE cliente_id = ?');
        return $stmt->execute([$id]);
    }
}
