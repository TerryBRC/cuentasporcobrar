<?php
// src/controllers/ClienteController.php
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {
    public function index() {
        $clientes = Cliente::getAll();
        include __DIR__ . '/../views/clientes/index.php';
    }

    public function show($id) {
        $cliente = Cliente::getById($id);
        include __DIR__ . '/../views/clientes/show.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            if ($nombre) {
                Cliente::create($nombre);
                header('Location: clientes');
                exit;
            }
        }
        include __DIR__ . '/../views/clientes/create.php';
    }

    public function edit($id) {
        $cliente = Cliente::getById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            if ($nombre) {
                Cliente::update($id, $nombre);
                header('Location: clientes');
                exit;
            }
        }
        include __DIR__ . '/../views/clientes/edit.php';
    }

    public function delete($id) {
        Cliente::delete($id);
        header('Location: clientes');
        exit;
    }
}
