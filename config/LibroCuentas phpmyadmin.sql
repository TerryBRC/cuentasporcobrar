-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 17-07-2025 a las 07:00:59
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `LibroCuentas`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertar_movimiento` (IN `p_cliente_id` INT, IN `p_fecha` DATE, IN `p_numero_comprobante` VARCHAR(50), IN `p_descripcion` VARCHAR(255), IN `p_debe` DECIMAL(10,2), IN `p_haber` DECIMAL(10,2))   BEGIN
    INSERT INTO movimientos_cliente (
        cliente_id, fecha, numero_comprobante, descripcion, debe, haber
    ) VALUES (
        p_cliente_id, p_fecha, p_numero_comprobante, p_descripcion, p_debe, p_haber
    );
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `balance_general_clientes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `balance_general_clientes` (
`cliente_id` int(11)
,`nombre` varchar(100)
,`total_creditos` decimal(32,2)
,`total_abonos` decimal(32,2)
,`saldo_final` decimal(33,2)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `cliente_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `identificacion` varchar(30) NOT NULL,
  `direccion` text NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`cliente_id`, `nombre`, `identificacion`, `direccion`, `telefono`, `activo`) VALUES
(1, 'prueba 1', '239882387687', 'leon', '89878454', 1),
(2, 'pruba 2', 'laksdfl', 'leon', '89859874', 1);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `libro_mayor_clientes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `libro_mayor_clientes` (
`movimiento_id` int(11)
,`cliente_id` int(11)
,`nombre` varchar(100)
,`fecha` date
,`numero_comprobante` varchar(50)
,`descripcion` varchar(255)
,`debe` decimal(10,2)
,`haber` decimal(10,2)
,`saldo` decimal(33,2)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_cliente`
--

CREATE TABLE `movimientos_cliente` (
  `movimiento_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `numero_comprobante` varchar(50) DEFAULT NULL,
  `descripcion` varchar(255) NOT NULL,
  `debe` decimal(10,2) DEFAULT 0.00,
  `haber` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `reporte_mensual_clientes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `reporte_mensual_clientes` (
`cliente_id` int(11)
,`mes` varchar(7)
,`total_debe` decimal(32,2)
,`total_haber` decimal(32,2)
,`saldo_mes` decimal(33,2)
);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`cliente_id`),
  ADD UNIQUE KEY `identificacion` (`identificacion`);

--
-- Indices de la tabla `movimientos_cliente`
--
ALTER TABLE `movimientos_cliente`
  ADD PRIMARY KEY (`movimiento_id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `cliente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `movimientos_cliente`
--
ALTER TABLE `movimientos_cliente`
  MODIFY `movimiento_id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Estructura para la vista `balance_general_clientes`
--
DROP TABLE IF EXISTS `balance_general_clientes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `balance_general_clientes`  AS SELECT `c`.`cliente_id` AS `cliente_id`, `c`.`nombre` AS `nombre`, sum(`m`.`debe`) AS `total_creditos`, sum(`m`.`haber`) AS `total_abonos`, sum(`m`.`debe` - `m`.`haber`) AS `saldo_final` FROM (`clientes` `c` left join `movimientos_cliente` `m` on(`c`.`cliente_id` = `m`.`cliente_id`)) GROUP BY `c`.`cliente_id`, `c`.`nombre` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `libro_mayor_clientes`
--
DROP TABLE IF EXISTS `libro_mayor_clientes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `libro_mayor_clientes`  AS SELECT `m`.`movimiento_id` AS `movimiento_id`, `m`.`cliente_id` AS `cliente_id`, `c`.`nombre` AS `nombre`, `m`.`fecha` AS `fecha`, `m`.`numero_comprobante` AS `numero_comprobante`, `m`.`descripcion` AS `descripcion`, `m`.`debe` AS `debe`, `m`.`haber` AS `haber`, sum(`m`.`debe` - `m`.`haber`) over ( partition by `m`.`cliente_id` order by `m`.`fecha`,`m`.`movimiento_id`) AS `saldo` FROM (`movimientos_cliente` `m` join `clientes` `c` on(`m`.`cliente_id` = `c`.`cliente_id`)) ORDER BY `m`.`cliente_id` ASC, `m`.`fecha` ASC, `m`.`movimiento_id` ASC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `reporte_mensual_clientes`
--
DROP TABLE IF EXISTS `reporte_mensual_clientes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `reporte_mensual_clientes`  AS SELECT `movimientos_cliente`.`cliente_id` AS `cliente_id`, date_format(`movimientos_cliente`.`fecha`,'%Y-%m') AS `mes`, sum(`movimientos_cliente`.`debe`) AS `total_debe`, sum(`movimientos_cliente`.`haber`) AS `total_haber`, sum(`movimientos_cliente`.`debe` - `movimientos_cliente`.`haber`) AS `saldo_mes` FROM `movimientos_cliente` GROUP BY `movimientos_cliente`.`cliente_id`, date_format(`movimientos_cliente`.`fecha`,'%Y-%m') ORDER BY `movimientos_cliente`.`cliente_id` ASC, date_format(`movimientos_cliente`.`fecha`,'%Y-%m') ASC ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `movimientos_cliente`
--
ALTER TABLE `movimientos_cliente`
  ADD CONSTRAINT `movimientos_cliente_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`cliente_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
