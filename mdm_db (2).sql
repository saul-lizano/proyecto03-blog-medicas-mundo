-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-04-2026 a las 12:33:03
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
-- Base de datos: `mdm_db`
--
CREATE DATABASE IF NOT EXISTS `mdm_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `mdm_db`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bloque`
--

CREATE TABLE IF NOT EXISTS `bloque` (
  `id_bloque` int(11) NOT NULL AUTO_INCREMENT,
  `id_categoria` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `texto` text NOT NULL,
  `orden` int(11) NOT NULL,
  `fecha_actualizacion` date NOT NULL,
  `icono` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_bloque`),
  KEY `fk_id_categoria_bloque` (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bloque`
--

INSERT INTO `bloque` (`id_bloque`, `id_categoria`, `titulo`, `descripcion`, `texto`, `orden`, `fecha_actualizacion`, `icono`) VALUES
(1, 1, 'Indefinidos', 'Sin duracion determinada', 'En España, el **contrato indefinido** es el acuerdo laboral sin fecha de finalización prevista y constituye la forma ordinaria de contratación.\r\n\r\n### Características principales\r\n\r\n* **Duración:** No tiene límite temporal.\r\n* **Jornada:** Puede ser completa, parcial o fija-discontinua.\r\n* **Formalización:** Puede ser verbal, aunque suele hacerse por escrito; algunas modalidades exigen forma escrita obligatoria.\r\n* **Periodo de prueba:** Según convenio o Estatuto de los Trabajadores.\r\n* **Despido:** Requiere causa legal o indemnización correspondiente.\r\n* **Seguridad Social:** Alta obligatoria por parte de la empresa.\r\n\r\n### Ventajas\r\n\r\n**Para el trabajador:**\r\n\r\n* Mayor estabilidad laboral\r\n* Derecho a indemnización en caso de despido improcedente\r\n* Mejor acceso a financiación o alquiler\r\n\r\n**Para la empresa:**\r\n\r\n* Posibles bonificaciones en determinados supuestos\r\n* Mayor retención de talento\r\n\r\n### Tipos frecuentes\r\n\r\n* Ordinario\r\n* Fijo-discontinuo\r\n* Indefinido adscrito a obra (sectores específicos)\r\n\r\n### Extinción\r\n\r\nPuede finalizar por:\r\n\r\n* Baja voluntaria\r\n* Jubilación\r\n* Despido disciplinario\r\n* Despido objetivo\r\n* Mutuo acuerdo\r\n\r\n### Marco legal\r\n\r\nRegulado principalmente por el Estatuto de los Trabajadores.\r\n', 1, '2026-04-28', '69f082f308d24_dream_xdkhnl2zpel.jpg'),
(2, 1, 'fefef', 'ejemplo', 'hola\r\nhola\r\n\r\nholaa\r\n\r\n\r\na', 2, '2026-04-28', '69f086efb2b38_dream_xdkhnl2zpel.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `orden` int(11) NOT NULL,
  `img_cat` varchar(255) NOT NULL,
  `id_madre` int(11) DEFAULT NULL,
  `fecha_actualizacion` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_categoria`),
  KEY `fk_id_madre` (`id_madre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`, `descripcion`, `orden`, `img_cat`, `id_madre`, `fecha_actualizacion`) VALUES
(1, 'Contratos', 'tipos de contratos', 1, '69f0816b58b3e_Mapa de Empatia.png', NULL, '2026-04-28'),
(2, 'Nominas', 'pa calcular tu salario', 1, '69f08286e0707_Gemini_Generated_Image_4w5r24w5r24w5r24.png', NULL, '2026-04-28'),
(3, 'Jornada', 'Según las horas de la jornada de trabajo', 1, '69f0834720963_reloj.webp', 1, '2026-04-28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `extra`
--

CREATE TABLE IF NOT EXISTS `extra` (
  `id_extra` int(11) NOT NULL AUTO_INCREMENT,
  `id_bloque` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `url` text NOT NULL,
  `fecha_actualizacion` date NOT NULL,
  PRIMARY KEY (`id_extra`),
  KEY `id_bloque` (`id_bloque`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `id_faq` int(11) NOT NULL AUTO_INCREMENT,
  `pregunta` text NOT NULL,
  `respuesta` text NOT NULL,
  `fecha_actualizacion` date NOT NULL DEFAULT current_timestamp(),
  `id_categoria` int(11) NOT NULL,
  PRIMARY KEY (`id_faq`),
  KEY `fk_id_categoria_faq` (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE IF NOT EXISTS `rol` (
  `id_rol` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(255) NOT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre_rol`) VALUES
(1, 'admin'),
(2, 'editora');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` text NOT NULL,
  `id_rol` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `id_rol` (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `email`, `password`, `nombre`, `id_rol`) VALUES
(2, 'medica@gmail.es', '$2y$10$TiIgnXN4bFTG.gqu.MNUYOB8z9BizZDCVckooUhbyn2m75gCb.pEO', 'Admin25', 1);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bloque`
--
ALTER TABLE `bloque`
  ADD CONSTRAINT `fk_id_categoria_bloque` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD CONSTRAINT `fk_id_madre` FOREIGN KEY (`id_madre`) REFERENCES `categoria` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `extra`
--
ALTER TABLE `extra`
  ADD CONSTRAINT `fk_id_bloque` FOREIGN KEY (`id_bloque`) REFERENCES `bloque` (`id_bloque`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `faq`
--
ALTER TABLE `faq`
  ADD CONSTRAINT `fk_id_categoria_faq` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
