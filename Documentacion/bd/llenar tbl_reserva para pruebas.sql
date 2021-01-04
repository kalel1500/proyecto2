-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-11-2018 a las 00:47:40
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_1819_canalsadrian`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_reserva`
--

DROP TABLE IF EXISTS `tbl_reserva`;
CREATE TABLE IF NOT EXISTS `tbl_reserva` (
  `id_reserva` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion_reserva` text COLLATE utf8_unicode_ci,
  `fechaRealizacion_reserva` datetime DEFAULT CURRENT_TIMESTAMP,
  `fechaInicio_reserva` datetime DEFAULT NULL,
  `fechaFinal_reserva` datetime DEFAULT NULL,
  `modoFinalizacion_reserva` enum('bien','incidencia','cancelada','pendiente','incidencia_colateral') COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_recurso` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_reserva`),
  KEY `FK_reserva_usuario` (`id_usuario`),
  KEY `FK_reserva_recurso` (`id_recurso`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Truncar tablas antes de insertar `tbl_reserva`
--

TRUNCATE TABLE `tbl_reserva`;
--
-- Volcado de datos para la tabla `tbl_reserva`
--
/*mismo */
INSERT INTO `tbl_reserva` (`descripcion_reserva`, `fechaRealizacion_reserva`, `fechaInicio_reserva`, `fechaFinal_reserva`, `modoFinalizacion_reserva`, `id_usuario`, `id_recurso`) VALUES
('pasado', '2018-11-27 00:40:23', '2018-11-26 08:00:00', '2018-11-26 11:00:00', 'pendiente', 3, 1),
('en proceso', '2018-11-27 00:40:25', '2018-11-26 12:00:00', '2018-11-28 09:00:00', 'pendiente', 3, 1),
('futuro', '2018-11-27 00:41:21', '2018-11-29 08:00:00', '2018-11-30 08:00:00', 'pendiente', 3, 1);

/*diferente*/
INSERT INTO `tbl_reserva` (`descripcion_reserva`, `fechaRealizacion_reserva`, `fechaInicio_reserva`, `fechaFinal_reserva`, `modoFinalizacion_reserva`, `id_usuario`, `id_recurso`) VALUES
('pasado2', '2018-11-27 00:40:23', '2018-11-26 08:00:00', '2018-11-26 11:00:00', 'pendiente', 3, 5),
('en proceso2', '2018-11-27 00:40:25', '2018-11-26 12:00:00', '2018-11-28 09:00:00', 'pendiente', 3, 6),
('futuro2', '2018-11-27 00:41:21', '2018-11-29 08:00:00', '2018-11-30 08:00:00', 'pendiente', 3, 14);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tbl_reserva`
--
ALTER TABLE `tbl_reserva`
  ADD CONSTRAINT `FK_reserva_recurso` FOREIGN KEY (`id_recurso`) REFERENCES `tbl_recurso` (`id_recurso`),
  ADD CONSTRAINT `FK_reserva_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `tbl_usuario` (`id_usuario`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
