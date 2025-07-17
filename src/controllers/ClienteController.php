<?php
// src/controllers/ClienteController.php
require_once __DIR__ . '/../models/Cliente.php'; // Correct path for models

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
            $identificacion = $_POST['identificacion'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $frecuencia = $_POST['frecuencia'] ?? ''; // Retrieve frequency from form

            // Add $activo to the Cliente::create call
            if ($nombre && $identificacion && $direccion && $telefono) { // Add validation for $activo if it's strictly required from form
                Cliente::create($nombre, $identificacion, $frecuencia, $direccion, $telefono);
                header('Location: ' . BASE_URL . '/clientes');
                exit;
            }
        }
        include __DIR__ . '/../views/clientes/create.php';
    }

    public function edit($id) {
        $cliente = Cliente::getById($id); // Get existing client data

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $identificacion = $_POST['identificacion'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            // Retrieve 'activo' from the form (checkbox value: 1 if checked, 0 if not present)
            $activo = isset($_POST['activo']) ? 1 : 0;

            if ($nombre) { // Basic validation
                // Pass 'activo' to the Cliente::update call
                Cliente::update($id, $nombre, $identificacion, $direccion, $telefono, $activo);
                header('Location: ' . BASE_URL . '/clientes');
                exit;
            }
        }
        include __DIR__ . '/../views/clientes/edit.php';
    }

    public function delete($id) {
        Cliente::delete($id);
        header('Location: ' . BASE_URL . '/clientes');
        exit;
    }
}