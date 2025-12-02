-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-11-2025 a las 11:44:22
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_contable`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diario_asientos`
--

CREATE TABLE `diario_asientos` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `glosa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `diario_asientos`
--

INSERT INTO `diario_asientos` (`id`, `fecha`, `glosa`) VALUES
(1, '2025-02-21', 'compra de una motocicleta ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diario_detalles`
--

CREATE TABLE `diario_detalles` (
  `id` int(11) NOT NULL,
  `asiento_id` int(11) NOT NULL,
  `cuenta_id` int(11) NOT NULL,
  `debe` decimal(14,2) DEFAULT 0.00,
  `haber` decimal(14,2) DEFAULT 0.00,
  `folio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `diario_detalles`
--

INSERT INTO `diario_detalles` (`id`, `asiento_id`, `cuenta_id`, `debe`, `haber`, `folio`) VALUES
(1, 1, 1, 5000.00, 0.00, 1),
(2, 1, 2, 500.00, 0.00, 2),
(3, 1, 1, 0.00, 5500.00, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre_completo` varchar(100) DEFAULT NULL,
  `rol` enum('admin','invitado') NOT NULL DEFAULT 'invitado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `nombre_completo`, `rol`) VALUES
(1, 'admin', '$2y$10$E/gL3h5j.C4B.3s0.Jg0a.UeG5.F.d/c3Qz/iY.o/bX.Z/w.K/z.K', 'Administrador del Sistema', 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_cuentas`
--

CREATE TABLE `plan_cuentas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `tipo` enum('Activo','Pasivo','Patrimonio','Ingreso','Gasto') NOT NULL,
  `saldo_inicial` decimal(14,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `plan_cuentas`
--

INSERT INTO `plan_cuentas` (`id`, `nombre`, `tipo`, `saldo_inicial`) VALUES
(1, 'caja', 'Activo', 0.00),
(2, 'IVA credito fiscal', 'Pasivo', 0.00),
(3, 'Gasto por servicio ', 'Pasivo', 1500.00),
(4, 'Banco', 'Activo', 0.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `diario_asientos`
--
ALTER TABLE `diario_asientos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `diario_detalles`
--
ALTER TABLE `diario_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asiento_id` (`asiento_id`),
  ADD KEY `cuenta_id` (`cuenta_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `plan_cuentas`
--
ALTER TABLE `plan_cuentas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `diario_asientos`
--
ALTER TABLE `diario_asientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `diario_detalles`
--
ALTER TABLE `diario_detalles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `plan_cuentas`
--
ALTER TABLE `plan_cuentas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `diario_detalles`
--
ALTER TABLE `diario_detalles`
  ADD CONSTRAINT `diario_detalles_ibfk_1` FOREIGN KEY (`asiento_id`) REFERENCES `diario_asientos` (`id`),
  ADD CONSTRAINT `diario_detalles_ibfk_2` FOREIGN KEY (`cuenta_id`) REFERENCES `plan_cuentas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
