-- Creación de la base de datos
drop database if exists tfgdb;
create database tfgdb CHARACTER SET utf8 COLLATE utf8_general_ci;
use tfgdb;

-- Creación del usuario dotandole de permisos
grant usage on *.* to tfguser@localhost identified by 'tfgpass';
grant all privileges on tfgdb.* to tfguser@localhost;

-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 02-07-2014 a las 14:37:25
-- Versión del servidor: 5.6.16
-- Versión de PHP: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `tfgdb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doc_final`
--

CREATE TABLE IF NOT EXISTS `doc_final` (
  `id_doc` int(11) NOT NULL AUTO_INCREMENT,
  `titulacion` varchar(80) DEFAULT NULL,
  `asignatura` varchar(80) DEFAULT NULL,
  `convocatoria` varchar(40) DEFAULT NULL,
  `instrucciones` text,
  `fecha` date DEFAULT NULL,
  `estado` enum('abierto','cerrado','publicado') NOT NULL,
  `id_padre` int(11) DEFAULT NULL,
  `Comentario` text NOT NULL,
  PRIMARY KEY (`id_doc`),
  KEY `id_padre` (`id_padre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=310 ;

--
-- Volcado de datos para la tabla `doc_final`
--

INSERT INTO `doc_final` (`id_doc`, `titulacion`, `asignatura`, `convocatoria`, `instrucciones`, `fecha`, `estado`, `id_padre`, `Comentario`) VALUES
(307, 'Ingeniería Informática (examen abierto).', 'Sistemas Operativos', 'Enero 2014', '', '2014-01-24', 'abierto', NULL, ''),
(308, 'Ingeniería Informática (examen cerrado).', 'Administración de Sistemas', 'Mayo 2014', '', '2014-05-09', 'cerrado', NULL, ''),
(309, 'Ingeniería Informática (examen publicado).', 'Administración de Sistemas', 'Junio 2014', '', '2014-06-17', 'publicado', NULL, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagen`
--

CREATE TABLE IF NOT EXISTS `imagen` (
  `id_imagen` int(11) NOT NULL AUTO_INCREMENT,
  `url` text,
  PRIMARY KEY (`id_imagen`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=405 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pregunta`
--

CREATE TABLE IF NOT EXISTS `pregunta` (
  `id_pregunta` int(11) NOT NULL AUTO_INCREMENT,
  `enunciado` text NOT NULL,
  `solucion` text,
  `explicacion` text,
  `puntuacion` int(11) NOT NULL DEFAULT '1',
  `posicion` int(11) NOT NULL,
  `id_problema` int(11) NOT NULL,
  PRIMARY KEY (`id_pregunta`),
  KEY `id_problema` (`id_problema`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=429 ;

--
-- Volcado de datos para la tabla `pregunta`
--

INSERT INTO `pregunta` (`id_pregunta`, `enunciado`, `solucion`, `explicacion`, `puntuacion`, `posicion`, `id_problema`) VALUES
(422, 'Primera pregunta del problema que no estará en ningún documento.', '', '', 2, 1, 128),
(423, 'Segunda pregunta del problema que no estará en ningún documento.', 'Solución de segunda pregunta del problema que no estará en ningún documento.', '', 1, 2, 128),
(424, 'Primera pregunta del problema que estará en un documento abierto.', '', '', 1, 1, 129),
(426, 'Primera pregunta del problema que estará en un documento publicado.', '', 'Explicación de primera pregunta del problema que estará en un documento publicado.', 1, 1, 131),
(427, 'Segunda pregunta del problema que estará en un documento publicado.', 'Solución de segunda pregunta del problema que estará en un documento publicado.', '', 2, 2, 131),
(428, 'Primera pregunta del problema que estará en un documento cerrado.', 'Solución de primera pregunta del problema que estará en un documento cerrado.', 'Explicación de primera pregunta del problema que estará en un documento cerrado.', 4, 1, 130);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `problema`
--

CREATE TABLE IF NOT EXISTS `problema` (
  `id_problema` int(11) NOT NULL AUTO_INCREMENT,
  `enunciado_general` text,
  `resumen` text NOT NULL,
  `id_padre` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_problema`),
  UNIQUE KEY `id_padre` (`id_padre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=132 ;

--
-- Volcado de datos para la tabla `problema`
--

INSERT INTO `problema` (`id_problema`, `enunciado_general`, `resumen`, `id_padre`) VALUES
(128, '', 'Problema que no estará en ningún documento.', NULL),
(129, 'Enunciado general del problema 2.', 'Problema que estará en un documento abierto.', NULL),
(130, '', 'Problema que estará en un documento cerrado.', NULL),
(131, '', 'Problema que estará en un documento publicado.', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `problema_doc_final`
--

CREATE TABLE IF NOT EXISTS `problema_doc_final` (
  `id_doc` int(11) NOT NULL,
  `id_problema` int(11) NOT NULL,
  `posicion` int(11) NOT NULL,
  `salto_columna` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_doc`,`id_problema`),
  UNIQUE KEY `id_doc` (`id_doc`,`posicion`),
  KEY `id_problema` (`id_problema`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `problema_doc_final`
--

INSERT INTO `problema_doc_final` (`id_doc`, `id_problema`, `posicion`, `salto_columna`) VALUES
(307, 129, 1, 0),
(308, 130, 1, 0),
(309, 131, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `problema_imagen`
--

CREATE TABLE IF NOT EXISTS `problema_imagen` (
  `id_problema` int(11) NOT NULL,
  `id_imagen` int(11) NOT NULL,
  `nombre_amigable` varchar(40) NOT NULL,
  PRIMARY KEY (`id_problema`,`id_imagen`),
  UNIQUE KEY `nombre_amigable` (`nombre_amigable`),
  KEY `id_imagen` (`id_imagen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `problema_tag`
--

CREATE TABLE IF NOT EXISTS `problema_tag` (
  `id_problema` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  PRIMARY KEY (`id_problema`,`id_tag`),
  KEY `id_tag` (`id_tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `problema_tag`
--

INSERT INTO `problema_tag` (`id_problema`, `id_tag`) VALUES
(128, 520),
(129, 520),
(128, 521),
(130, 521),
(129, 522),
(130, 523),
(131, 523),
(131, 524);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `id_tag` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) NOT NULL,
  PRIMARY KEY (`id_tag`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=525 ;

--
-- Volcado de datos para la tabla `tag`
--

INSERT INTO `tag` (`id_tag`, `nombre`) VALUES
(523, 'ASI'),
(524, 'dhcp'),
(522, 'hilos'),
(520, 'SO'),
(521, 'sockets');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `doc_final`
--
ALTER TABLE `doc_final`
  ADD CONSTRAINT `doc_final_ibfk_1` FOREIGN KEY (`id_padre`) REFERENCES `doc_final` (`id_doc`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `pregunta`
--
ALTER TABLE `pregunta`
  ADD CONSTRAINT `pregunta_ibfk_1` FOREIGN KEY (`id_problema`) REFERENCES `problema` (`id_problema`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `problema`
--
ALTER TABLE `problema`
  ADD CONSTRAINT `problema_ibfk_1` FOREIGN KEY (`id_padre`) REFERENCES `problema` (`id_problema`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `problema_doc_final`
--
ALTER TABLE `problema_doc_final`
  ADD CONSTRAINT `problema_doc_final_ibfk_1` FOREIGN KEY (`id_doc`) REFERENCES `doc_final` (`id_doc`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `problema_doc_final_ibfk_2` FOREIGN KEY (`id_problema`) REFERENCES `problema` (`id_problema`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `problema_imagen`
--
ALTER TABLE `problema_imagen`
  ADD CONSTRAINT `problema_imagen_ibfk_1` FOREIGN KEY (`id_problema`) REFERENCES `problema` (`id_problema`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `problema_imagen_ibfk_2` FOREIGN KEY (`id_imagen`) REFERENCES `imagen` (`id_imagen`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `problema_tag`
--
ALTER TABLE `problema_tag`
  ADD CONSTRAINT `problema_tag_ibfk_1` FOREIGN KEY (`id_problema`) REFERENCES `problema` (`id_problema`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `problema_tag_ibfk_2` FOREIGN KEY (`id_tag`) REFERENCES `tag` (`id_tag`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
