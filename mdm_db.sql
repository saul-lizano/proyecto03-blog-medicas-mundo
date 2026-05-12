-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-05-2026 a las 21:00:40
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bloque`
--

CREATE TABLE `bloque` (
  `id_bloque` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `texto` text NOT NULL,
  `orden` int(11) NOT NULL,
  `fecha_actualizacion` date NOT NULL,
  `icono` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bloque`
--

INSERT INTO `bloque` (`id_bloque`, `id_categoria`, `titulo`, `descripcion`, `texto`, `orden`, `fecha_actualizacion`, `icono`) VALUES
(1, 1, 'Indefinidos', 'Sin duracion determinada', 'En España, el **contrato indefinido** es el acuerdo laboral sin fecha de finalización prevista y constituye la forma ordinaria de contratación.\r\n\r\n### Características principales\r\n\r\n* **Duración:** No tiene límite temporal.\r\n* **Jornada:** Puede ser completa, parcial o fija-discontinua.\r\n* **Formalización:** Puede ser verbal, aunque suele hacerse por escrito; algunas modalidades exigen forma escrita obligatoria.\r\n* **Periodo de prueba:** Según convenio o Estatuto de los Trabajadores.\r\n* **Despido:** Requiere causa legal o indemnización correspondiente.\r\n* **Seguridad Social:** Alta obligatoria por parte de la empresa.\r\n\r\n### Ventajas\r\n\r\n**Para el trabajador:**\r\n\r\n* Mayor estabilidad laboral\r\n* Derecho a indemnización en caso de despido improcedente\r\n* Mejor acceso a financiación o alquiler\r\n\r\n**Para la empresa:**\r\n\r\n* Posibles bonificaciones en determinados supuestos\r\n* Mayor retención de talento\r\n\r\n### Tipos frecuentes\r\n\r\n* Ordinario\r\n* Fijo-discontinuo\r\n* Indefinido adscrito a obra (sectores específicos)\r\n\r\n### Extinción\r\n\r\nPuede finalizar por:\r\n\r\n* Baja voluntaria\r\n* Jubilación\r\n* Despido disciplinario\r\n* Despido objetivo\r\n* Mutuo acuerdo\r\n\r\n### Marco legal\r\n\r\nRegulado principalmente por el Estatuto de los Trabajadores.\r\n', 1, '2026-04-28', '69f082f308d24_dream_xdkhnl2zpel.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `orden` int(11) NOT NULL,
  `img_cat` varchar(255) NOT NULL,
  `id_madre` int(11) DEFAULT NULL,
  `fecha_actualizacion` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`, `descripcion`, `orden`, `img_cat`, `id_madre`, `fecha_actualizacion`) VALUES
(1, 'Contratos', 'tipos de contratos', 1, '69f0816b58b3e_Mapa de Empatia.png', NULL, '2026-04-28'),
(2, 'Nominas', 'pa calcular tu salario', 1, '69f08286e0707_Gemini_Generated_Image_4w5r24w5r24w5r24.png', NULL, '2026-04-28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `extra`
--

CREATE TABLE `extra` (
  `id_extra` int(11) NOT NULL,
  `id_bloque` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `url` text NOT NULL,
  `fecha_actualizacion` date NOT NULL,
  `tipo` enum('imagen','enlace') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `extra`
--

INSERT INTO `extra` (`id_extra`, `id_bloque`, `descripcion`, `url`, `fecha_actualizacion`, `tipo`) VALUES
(1, 1, 'Medicos', '69f723ac5b024_placeholder_bloque.png', '2026-05-03', 'imagen');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faq`
--

CREATE TABLE `faq` (
  `id_faq` int(11) NOT NULL,
  `pregunta` text NOT NULL,
  `respuesta` text NOT NULL,
  `fecha_actualizacion` date NOT NULL DEFAULT current_timestamp(),
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `faq`
--

INSERT INTO `faq` (`id_faq`, `pregunta`, `respuesta`, `fecha_actualizacion`, `id_categoria`) VALUES
(1, 'hoa', 'frf', '2026-05-02', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` text NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `email`, `password`, `nombre`, `id_rol`) VALUES
(2, 'medica@gmail.es', '$2y$10$TiIgnXN4bFTG.gqu.MNUYOB8z9BizZDCVckooUhbyn2m75gCb.pEO', 'Admin25', 1),
(3, 'algo2@gmail.com', '$2y$10$YzNkz.96pEaYFx.OKpTkBur.5XeeeiHA1X016JsDm4Eqf5no7tBdG', 'David', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bloque`
--
ALTER TABLE `bloque`
  ADD PRIMARY KEY (`id_bloque`),
  ADD KEY `fk_id_categoria_bloque` (`id_categoria`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`),
  ADD KEY `fk_id_madre` (`id_madre`);

--
-- Indices de la tabla `extra`
--
ALTER TABLE `extra`
  ADD PRIMARY KEY (`id_extra`),
  ADD KEY `fk_id_bloque` (`id_bloque`);

--
-- Indices de la tabla `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id_faq`),
  ADD KEY `fk_id_categoria_faq` (`id_categoria`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuario_email` (`email`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bloque`
--
ALTER TABLE `bloque`
  MODIFY `id_bloque` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `extra`
--
ALTER TABLE `extra`
  MODIFY `id_extra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `faq`
--
ALTER TABLE `faq`
  MODIFY `id_faq` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
