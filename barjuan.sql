-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-12-2023 a las 22:30:15
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `barjuan`
--
CREATE DATABASE IF NOT EXISTS `barjuan` DEFAULT CHARACTER SET latin1 COLLATE latin1_spanish_ci;
USE `barjuan`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comandas`
--

CREATE TABLE `comandas` (
  `idComanda` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `idMesa` int(11) NOT NULL,
  `comensales` int(11) NOT NULL,
  `detalles` varchar(250) COLLATE latin1_spanish_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'TRUE en espera, FALSE finalizada '
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- RELACIONES PARA LA TABLA `comandas`:
--   `idMesa`
--       `mesa` -> `idMesa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lineascomandas`
--

CREATE TABLE `lineascomandas` (
  `idlinea` int(11) NOT NULL,
  `idComanda` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantidad` decimal(8,2) NOT NULL,
  `entregado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'false sin entregar, true entregado'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- RELACIONES PARA LA TABLA `lineascomandas`:
--   `idComanda`
--       `comandas` -> `idComanda`
--   `idProducto`
--       `productos` -> `idProducto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lineaspedidos`
--

CREATE TABLE `lineaspedidos` (
  `idlinea` int(11) NOT NULL,
  `idPedido` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantidad` decimal(8,2) NOT NULL,
  `entregado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- RELACIONES PARA LA TABLA `lineaspedidos`:
--   `idPedido`
--       `pedidos` -> `idPedidos`
--   `idProducto`
--       `productos` -> `idProducto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `idMesa` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `comensales` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- RELACIONES PARA LA TABLA `mesa`:
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `idPedidos` int(11) NOT NULL,
  `idProveedor` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `detalles` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- RELACIONES PARA LA TABLA `pedidos`:
--   `idProveedor`
--       `proveedores` -> `idProveedor`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idProducto` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `descripcion` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `precio` decimal(8,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- RELACIONES PARA LA TABLA `productos`:
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `idProveedor` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `cif` varchar(9) COLLATE latin1_spanish_ci NOT NULL,
  `direccion` text COLLATE latin1_spanish_ci NOT NULL,
  `telefono` int(11) DEFAULT NULL,
  `email` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `contacto` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- RELACIONES PARA LA TABLA `proveedores`:
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `idStock` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(8,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- RELACIONES PARA LA TABLA `stock`:
--   `id_producto`
--       `productos` -> `idProducto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE `tickets` (
  `idTicket` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `idComanda` int(11) NOT NULL,
  `importe` decimal(10,2) NOT NULL,
  `pagado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- RELACIONES PARA LA TABLA `tickets`:
--   `idComanda`
--       `comandas` -> `idComanda`
--

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comandas`
--
ALTER TABLE `comandas`
  ADD PRIMARY KEY (`idComanda`),
  ADD KEY `comandas_mesa_idMesa_fk` (`idMesa`);

--
-- Indices de la tabla `lineascomandas`
--
ALTER TABLE `lineascomandas`
  ADD PRIMARY KEY (`idlinea`),
  ADD KEY `lineascomanda_comandas_idComanda_fk` (`idComanda`),
  ADD KEY `lineascomanda_productos_idProducto_fk` (`idProducto`);

--
-- Indices de la tabla `lineaspedidos`
--
ALTER TABLE `lineaspedidos`
  ADD PRIMARY KEY (`idlinea`),
  ADD KEY `lineasPedidos_pedidos_idPedidos_fk` (`idPedido`),
  ADD KEY `lineasPedidos_productos_idProducto_fk` (`idProducto`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`idMesa`),
  ADD UNIQUE KEY `mesa_pk2` (`nombre`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idPedidos`),
  ADD KEY `pedidos_proveedores_idProveedor_fk` (`idProveedor`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idProducto`),
  ADD UNIQUE KEY `nombre_unique` (`nombre`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`idProveedor`),
  ADD UNIQUE KEY `proveedores_pk2` (`nombre`),
  ADD UNIQUE KEY `proveedores_pk3` (`cif`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`idStock`),
  ADD KEY `stock_producto__fk` (`id_producto`);

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`idTicket`),
  ADD KEY `tickets_comandas_idComanda_fk` (`idComanda`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comandas`
--
ALTER TABLE `comandas`
  MODIFY `idComanda` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lineascomandas`
--
ALTER TABLE `lineascomandas`
  MODIFY `idlinea` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lineaspedidos`
--
ALTER TABLE `lineaspedidos`
  MODIFY `idlinea` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `idMesa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idPedidos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `idProveedor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `idStock` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `idTicket` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comandas`
--
ALTER TABLE `comandas`
  ADD CONSTRAINT `comandas_mesa_idMesa_fk` FOREIGN KEY (`idMesa`) REFERENCES `mesa` (`idMesa`);

--
-- Filtros para la tabla `lineascomandas`
--
ALTER TABLE `lineascomandas`
  ADD CONSTRAINT `lineascomanda_comandas_idComanda_fk` FOREIGN KEY (`idComanda`) REFERENCES `comandas` (`idComanda`),
  ADD CONSTRAINT `lineascomanda_productos_idProducto_fk` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`idProducto`);

--
-- Filtros para la tabla `lineaspedidos`
--
ALTER TABLE `lineaspedidos`
  ADD CONSTRAINT `lineasPedidos_pedidos_idPedidos_fk` FOREIGN KEY (`idPedido`) REFERENCES `pedidos` (`idPedidos`),
  ADD CONSTRAINT `lineasPedidos_productos_idProducto_fk` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`idProducto`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_proveedores_idProveedor_fk` FOREIGN KEY (`idProveedor`) REFERENCES `proveedores` (`idProveedor`);

--
-- Filtros para la tabla `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_producto__fk` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idProducto`);

--
-- Filtros para la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_comandas_idComanda_fk` FOREIGN KEY (`idComanda`) REFERENCES `comandas` (`idComanda`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
