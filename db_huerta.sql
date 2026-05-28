-- -------------------------------------------------------------------------
-- Archivo: db_huerta.sql
-- Descripción: Script SQL que define la estructura completa de la base de datos, incluyendo tablas para plantas, bancales, seguimiento, usuarios y roles, así como sus relaciones e índices.
-- Componente: Base de Datos
-- -------------------------------------------------------------------------


-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 24-05-2026 a las 14:49:18
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
-- Base de datos: `db_huerta`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bancales`
--

CREATE TABLE `bancales` (
  `id` int(11) NOT NULL,
  `nombre_bancal` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bancales`
--

INSERT INTO `bancales` (`id`, `nombre_bancal`, `descripcion`) VALUES
(1, 'Bancal A (Hortalizas de hoja)', 'Destinado a cultivos de hojas verdes con alta rotación.'),
(2, 'Bancal B (Frutos y Raíces)', 'Destinado a cultivos de ciclo más largo y raíces.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo_plantas`
--

CREATE TABLE `catalogo_plantas` (
  `id` int(11) NOT NULL,
  `nombre_comun` varchar(100) NOT NULL,
  `tiempo_cosecha` varchar(50) DEFAULT NULL,
  `dias_cosecha_num` int(11) DEFAULT NULL,
  `id_epoca` int(11) DEFAULT NULL,
  `id_tipo_siembra` int(11) DEFAULT NULL,
  `id_tipo_cultivo` int(11) DEFAULT NULL,
  `id_grupo_rotacion` int(11) DEFAULT NULL,
  `riego` varchar(100) DEFAULT NULL,
  `distancia_sugerida` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `catalogo_plantas`
--

INSERT INTO `catalogo_plantas` (`id`, `nombre_comun`, `tiempo_cosecha`, `dias_cosecha_num`, `id_epoca`, `id_tipo_siembra`, `id_tipo_cultivo`, `id_grupo_rotacion`, `riego`, `distancia_sugerida`) VALUES
(1, 'Acelga', '3 - 4 meses', 80, 1, 1, 1, NULL, 'Mantener humedad', 30),
(2, 'Espinaca', '2 - 3 meses', 90, 1, 1, 1, NULL, 'Ligero y frecuente', NULL),
(3, 'Lechuga', '2 - 4 meses', 60, 1, 2, 1, NULL, 'Ligero y frecuente', NULL),
(4, 'Perejil', '3 meses', 90, 1, 1, 1, NULL, 'Frecuente, mantener humedad', NULL),
(5, 'Rabanitos', '4 - 6 semanas', 45, 1, 1, 2, NULL, 'Cada día', NULL),
(6, 'Zanahoria', '3 - 4 meses', 120, 1, 1, 2, NULL, 'Cada día', NULL),
(7, 'Apio', '6 - 8 meses', NULL, 2, 2, 5, NULL, 'Frecuente, sin encharcar', NULL),
(8, 'Morrón/Ají', '5 - 6 meses', 150, 2, 2, 3, NULL, 'Generoso y abonado', 15),
(9, 'Berenjena', '4 - 5 meses', 140, 2, 2, 3, NULL, 'Mantener humedad', 15),
(10, 'Tomate', '4 meses', 120, 2, 2, 3, NULL, 'Frecuente, mantener humedad', 20),
(11, 'Arveja', '4 - 5 meses', 120, 2, 1, 4, NULL, 'Mantener humedad', NULL),
(12, 'Haba', '5 meses', NULL, 2, 1, 4, NULL, 'Mantener humedad', NULL),
(13, 'Chaucha', '3 - 4 meses', 120, 2, 1, 4, NULL, 'Mantener humedad', NULL),
(14, 'Rúcula', '1,5 meses', NULL, 1, 1, 1, NULL, 'Abundante', NULL),
(15, 'Achicoria', '1,5 meses', 45, 1, 1, 1, NULL, 'Mantener humedad', NULL),
(16, 'Hinojo', '3 - 4 meses', 120, 2, 1, 5, NULL, 'Mantener humedad', NULL),
(17, 'Zapallos/Zucchini', '3 - 4 meses', 120, 2, 1, 3, NULL, 'Generoso y abonado', 60),
(18, 'Melón', '3 meses', 100, 2, 1, 3, NULL, 'Frecuente, mantener humedad', NULL),
(19, 'Pepino', '2.5 meses', 90, 2, 1, 3, NULL, 'Mantener humedad', NULL),
(20, 'Sandía', '4 - 5 meses', 120, 2, 1, 3, NULL, 'Frecuente, mantener humedad', NULL),
(21, 'Cebolla', '6 - 8 meses', 120, 3, 2, 5, NULL, 'Escaso y menos a final de ciclo', NULL),
(22, 'Verdeo', '2 - 3 meses', 90, 3, 2, 5, NULL, 'Mantener humedad', NULL),
(23, 'Remolacha', '5 - 6 meses', 65, 2, 1, 2, NULL, 'Mantener humedad', 20),
(24, 'Maíz', '4 - 5 meses', 120, 2, 1, 3, NULL, 'Abundante', 30),
(25, 'Albahaca', '2.5 meses', 75, 2, 2, 1, NULL, 'Generoso sin excesos', NULL),
(26, 'Puerro', '6 - 7 meses', NULL, 4, 2, 5, NULL, 'Mantener humedad', 10),
(27, 'Brócoli', '5 - 6 meses', NULL, 4, 2, 6, NULL, 'Espaciado', NULL),
(28, 'Coliflor', '5 - 7 meses', NULL, 4, 2, 6, NULL, 'Mantener humedad', NULL),
(29, 'Repollo', '4 - 6 meses', 120, 4, 2, 6, NULL, 'Mantener humedad', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `epocas`
--

CREATE TABLE `epocas` (
  `id` int(11) NOT NULL,
  `nombre_epoca` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `epocas`
--

INSERT INTO `epocas` (`id`, `nombre_epoca`) VALUES
(1, 'Jun/Jul/Ago (Invierno)'),
(2, 'Set/Oct/Nov (Primavera)'),
(3, 'Dic/Ene/Feb (Verano)'),
(4, 'Mar/Abr/May (Otoño)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos_rotacion`
--

CREATE TABLE `grupos_rotacion` (
  `id` int(11) NOT NULL,
  `nombre_grupo` varchar(50) DEFAULT NULL,
  `sugerencia_siguiente` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupos_rotacion`
--

INSERT INTO `grupos_rotacion` (`id`, `nombre_grupo`, `sugerencia_siguiente`, `descripcion`) VALUES
(1, 'Hojas', 'Raíces', NULL),
(2, 'Raíces', 'Frutos', NULL),
(3, 'Frutos', 'Legumbres', NULL),
(4, 'Legumbres', 'Hojas', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_usuarios`
--

CREATE TABLE `historial_usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `nombre_real` varchar(100) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `realizado_por` int(11) DEFAULT NULL,
  `fecha_baja` datetime DEFAULT current_timestamp(),
  `fecha_alta_original` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_usuarios`
--

INSERT INTO `historial_usuarios` (`id`, `nombre_usuario`, `nombre_real`, `observaciones`, `realizado_por`, `fecha_baja`, `fecha_alta_original`) VALUES
(1, 'antiguo_user', 'Usuario Ejemplo Baja', 'Baja por finalización de pruebas externas', 1, '2026-05-24 10:00:00', '2026-01-10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plantas`
--

CREATE TABLE `plantas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `variedad` varchar(100) DEFAULT NULL,
  `id_epoca` int(11) DEFAULT NULL,
  `id_grupo_rotacion` int(11) DEFAULT NULL,
  `id_tipo_siembra` int(11) DEFAULT NULL,
  `id_tipo_cultivo` int(11) DEFAULT NULL,
  `distancia` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_siembra` date DEFAULT NULL,
  `id_bancal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `plantas`
--

INSERT INTO `plantas` (`id`, `nombre`, `variedad`, `id_epoca`, `id_grupo_rotacion`, `id_tipo_siembra`, `id_tipo_cultivo`, `distancia`, `observaciones`, `fecha_siembra`, `id_bancal`) VALUES
(1, 'Brócoli', 'Calabrese', 4, NULL, 2, NULL, 30, 'Crecimiento inicial óptimo', '2026-04-05', 1),
(2, 'Remolacha', 'Early Wonder', 2, NULL, 1, NULL, 20, 'Siembra directa exitosa', '2026-04-10', 2),
(3, 'Espinaca', 'Hoja Ancha (Genovesa)', 1, NULL, 1, NULL, 5, 'Controlar riego matutino', '2026-04-15', 1),
(4, 'Zapallos/Zucchini', 'Zucchini Amarillo', 2, NULL, 1, NULL, 60, 'Requiere tutorado pronto', '2026-04-21', 2),
(5, 'Rúcula', 'Cultivata', 1, NULL, 1, NULL, 5, 'Germinación rápida', '2026-05-01', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre_rol`) VALUES
(1, 'Admin Principal'),
(2, 'Administrador'),
(3, 'Operador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimiento`
--

CREATE TABLE `seguimiento` (
  `id` int(11) NOT NULL,
  `id_planta` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `accion` enum('Riego','Abono','Poda','Cosecha','Otro') NOT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `seguimiento`
--

INSERT INTO `seguimiento` (`id`, `id_planta`, `fecha`, `accion`, `observaciones`) VALUES
(1, 1, '2026-04-09', 'Riego', 'Riego general programado por la mañana'),
(2, 2, '2026-04-09', 'Riego', 'Riego general programado por la mañana'),
(3, 2, '2026-04-10', 'Abono', 'Aplicación de compost orgánico en la base'),
(4, 3, '2026-04-12', 'Riego', 'Riego ligero para mantener humedad superficial'),
(5, 4, '2026-04-21', 'Abono', 'Abonado de cobertura post-trasplante'),
(6, 5, '2026-05-05', 'Riego', 'Riego abundante tras la aparición de los primeros brotes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_cultivo`
--

CREATE TABLE `tipos_cultivo` (
  `id` int(11) NOT NULL,
  `nombre_tipo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_cultivo`
--

INSERT INTO `tipos_cultivo` (`id`, `nombre_tipo`) VALUES
(1, 'Hortalizas de Hoja'),
(2, 'Hortalizas de Raíz'),
(3, 'Hortalizas de Fruto'),
(4, 'Legumbres'),
(5, 'Bulbos'),
(6, 'Crucíferas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_siembra`
--

CREATE TABLE `tipos_siembra` (
  `id` int(11) NOT NULL,
  `nombre_tipo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_siembra`
--

INSERT INTO `tipos_siembra` (`id`, `nombre_tipo`) VALUES
(1, 'Siembra directa'),
(2, 'Siembra en semillero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre_real` varchar(100) DEFAULT NULL,
  `id_rol` int(11) NOT NULL,
  `fecha_alta` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `password`, `nombre_real`, `id_rol`, `fecha_alta`) VALUES
(1, 'operador_raiz', '$2y$10$rtyM8UhOdI5OyUjE1U0dieQ.HWExt8.ICOHO8ciZRZBtNyFtEh5N.', 'Administrador Demo', 1, '2026-05-24'),
(2, 'huerto_operador', '$2y$10$CVuHp36ziS09NZPFiCnd6.E92urQFesa15.MN1nDMB5BYxrVKWzxy', 'Operador Demo', 3, '2026-05-24');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bancales`
--
ALTER TABLE `bancales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `catalogo_plantas`
--
ALTER TABLE `catalogo_plantas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_epoca` (`id_epoca`),
  ADD KEY `id_tipo_siembra` (`id_tipo_siembra`),
  ADD KEY `id_tipo_cultivo` (`id_tipo_cultivo`),
  ADD KEY `id_grupo_rotacion` (`id_grupo_rotacion`);

--
-- Indices de la tabla `epocas`
--
ALTER TABLE `epocas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `grupos_rotacion`
--
ALTER TABLE `grupos_rotacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_usuarios`
--
ALTER TABLE `historial_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_realizado_por` (`realizado_por`);

--
-- Indices de la tabla `plantas`
--
ALTER TABLE `plantas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_bancal` (`id_bancal`),
  ADD KEY `fk_plantas_epoca` (`id_epoca`),
  ADD KEY `fk_plantas_rotacion` (`id_grupo_rotacion`),
  ADD KEY `fk_plantas_siembra` (`id_tipo_siembra`),
  ADD KEY `fk_plantas_cultivo` (`id_tipo_cultivo`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_planta` (`id_planta`);

--
-- Indices de la tabla `tipos_cultivo`
--
ALTER TABLE `tipos_cultivo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_siembra`
--
ALTER TABLE `tipos_siembra`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bancales`
--
ALTER TABLE `bancales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `catalogo_plantas`
--
ALTER TABLE `catalogo_plantas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `epocas`
--
ALTER TABLE `epocas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `grupos_rotacion`
--
ALTER TABLE `grupos_rotacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `historial_usuarios`
--
ALTER TABLE `historial_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `plantas`
--
ALTER TABLE `plantas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tipos_cultivo`
--
ALTER TABLE `tipos_cultivo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tipos_siembra`
--
ALTER TABLE `tipos_siembra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `catalogo_plantas`
--
ALTER TABLE `catalogo_plantas`
  ADD CONSTRAINT `catalogo_plantas_ibfk_1` FOREIGN KEY (`id_epoca`) REFERENCES `epocas` (`id`),
  ADD CONSTRAINT `catalogo_plantas_ibfk_2` FOREIGN KEY (`id_tipo_siembra`) REFERENCES `tipos_siembra` (`id`),
  ADD CONSTRAINT `catalogo_plantas_ibfk_3` FOREIGN KEY (`id_tipo_cultivo`) REFERENCES `tipos_cultivo` (`id`),
  ADD CONSTRAINT `catalogo_plantas_ibfk_4` FOREIGN KEY (`id_grupo_rotacion`) REFERENCES `grupos_rotacion` (`id`);

--
-- Filtros para la tabla `historial_usuarios`
--
ALTER TABLE `historial_usuarios`
  ADD CONSTRAINT `fk_realizado_por` FOREIGN KEY (`realizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `plantas`
--
ALTER TABLE `plantas`
  ADD CONSTRAINT `fk_plantas_cultivo` FOREIGN KEY (`id_tipo_cultivo`) REFERENCES `tipos_cultivo` (`id`),
  ADD CONSTRAINT `fk_plantas_epoca` FOREIGN KEY (`id_epoca`) REFERENCES `epocas` (`id`),
  ADD CONSTRAINT `fk_plantas_rotacion` FOREIGN KEY (`id_grupo_rotacion`) REFERENCES `grupos_rotacion` (`id`),
  ADD CONSTRAINT `fk_plantas_siembra` FOREIGN KEY (`id_tipo_siembra`) REFERENCES `tipos_siembra` (`id`),
  ADD CONSTRAINT `plantas_ibfk_1` FOREIGN KEY (`id_bancal`) REFERENCES `bancales` (`id`);

--
-- Filtros para la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  ADD CONSTRAINT `seguimiento_ibfk_1` FOREIGN KEY (`id_planta`) REFERENCES `plantas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
