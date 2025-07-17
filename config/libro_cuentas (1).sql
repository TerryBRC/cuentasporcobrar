
-- Crear base de datos
CREATE DATABASE IF NOT EXISTS LibroCuentas;
USE LibroCuentas;

-- Tabla de Clientes
CREATE TABLE IF NOT EXISTS clientes (
    cliente_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    identificacion VARCHAR(30) UNIQUE NOT NULL,
    direccion TEXT,
    telefono VARCHAR(20),
    activo BOOLEAN DEFAULT TRUE,
    frecuencia_pago ENUM('Semanal', 'Quincenal', 'Mensual') DEFAULT 'Semanal'
);

-- Tabla de Movimientos estilo libro mayor
CREATE TABLE IF NOT EXISTS movimientos_cliente (
    movimiento_id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    numero_comprobante VARCHAR(50),
    descripcion VARCHAR(255) NOT NULL,
    debe DECIMAL(10,2) DEFAULT 0.00,
    haber DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (cliente_id) REFERENCES clientes(cliente_id)
);

-- Vista de libro mayor con saldo acumulado por cliente
CREATE OR REPLACE VIEW libro_mayor_clientes AS
SELECT
    m.movimiento_id,
    m.cliente_id,
    c.nombre,
    m.fecha,
    m.numero_comprobante,
    m.descripcion,
    m.debe,
    m.haber,
    SUM(m.debe - m.haber) OVER (
        PARTITION BY m.cliente_id ORDER BY m.fecha, m.movimiento_id
    ) AS saldo
FROM movimientos_cliente m
JOIN clientes c ON m.cliente_id = c.cliente_id
ORDER BY m.cliente_id, m.fecha, m.movimiento_id;

-- Procedimiento para insertar un nuevo movimiento
DELIMITER $$

CREATE PROCEDURE insertar_movimiento (
    IN p_cliente_id INT,
    IN p_numero_comprobante VARCHAR(50),
    IN p_descripcion VARCHAR(255),
    IN p_debe DECIMAL(10,2),
    IN p_haber DECIMAL(10,2)
)
BEGIN
    INSERT INTO movimientos_cliente (
        cliente_id, numero_comprobante, descripcion, debe, haber
    ) VALUES (
        p_cliente_id, p_numero_comprobante, p_descripcion, p_debe, p_haber
    );
END $$

DELIMITER ;

-- Vista: Reporte mensual por cliente (total debe, haber, saldo por mes)
CREATE OR REPLACE VIEW reporte_mensual_clientes AS
SELECT
    cliente_id,
    DATE_FORMAT(fecha, '%Y-%m') AS mes,
    SUM(debe) AS total_debe,
    SUM(haber) AS total_haber,
    SUM(debe - haber) AS saldo_mes
FROM movimientos_cliente
GROUP BY cliente_id, mes
ORDER BY cliente_id, mes;

-- Vista: Balance general por cliente (resumen total)
CREATE OR REPLACE VIEW balance_general_clientes AS
SELECT
    c.cliente_id,
    c.nombre,
    SUM(m.debe) AS total_creditos,
    SUM(m.haber) AS total_abonos,
    SUM(m.debe - m.haber) AS saldo_final
FROM clientes c
LEFT JOIN movimientos_cliente m ON c.cliente_id = m.cliente_id
GROUP BY c.cliente_id, c.nombre;
