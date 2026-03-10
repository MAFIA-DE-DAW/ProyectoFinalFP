-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-03-2026 a las 10:47:21
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ecogotchi`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entorno`
--

CREATE TABLE `entorno` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nivel_ecologico` int(11) DEFAULT 50,
  `estado_entorno` varchar(30) DEFAULT 'normal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entorno`
--

INSERT INTO `entorno` (`id`, `id_usuario`, `nivel_ecologico`, `estado_entorno`) VALUES
(1, 1, 70, 'normal'),
(2, 3, 50, 'normal'),
(3, 2, 65, 'normal'),
(4, 4, 100, 'normal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascotas`
--

CREATE TABLE `mascotas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL,
  `hambre` int(11) DEFAULT 80,
  `sueno` int(11) DEFAULT 80,
  `diversion` int(11) DEFAULT 80,
  `higiene` int(11) DEFAULT 80,
  `salud` int(11) DEFAULT 100,
  `fecha_ultima_actualizacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mascotas`
--

INSERT INTO `mascotas` (`id`, `id_usuario`, `nombre`, `tipo`, `color`, `hambre`, `sueno`, `diversion`, `higiene`, `salud`, `fecha_ultima_actualizacion`) VALUES
(4, 3, 'Jorge', 'fantasia', 'azul', 16, 58, 16, 58, 100, '2026-03-09 15:43:52'),
(6, 2, 'jimmy', 'animal', 'azul', 22, 91, 22, 51, 100, '2026-03-09 17:39:20'),
(9, 4, 'Kevin', 'fantasia', 'verde', 60, 82, 60, 80, 100, '2026-03-10 09:59:38'),
(10, 1, 'Luisito', 'animal', 'azul', 46, 63, 46, 63, 100, '2026-03-10 10:42:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `misiones`
--

CREATE TABLE `misiones` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `recompensa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `misiones`
--

INSERT INTO `misiones` (`id`, `titulo`, `descripcion`, `recompensa`) VALUES
(1, 'Reciclar plástico', 'Hoy reciclaste una botella de plástico', 10),
(2, 'Usar transporte público', 'Evita el coche hoy', 15),
(3, 'Ahorrar agua', 'Reduce el tiempo en la ducha', 10),
(4, 'Apagar luces', 'Apaga luces innecesarias', 5),
(5, 'Reutilizar bolsa', 'Usa una bolsa reutilizable al hacer compras', 10),
(6, 'Separar residuos', 'Separa papel, plástico y orgánico correctamente', 10),
(7, 'Desconectar cargadores', 'Desconecta cargadores que no estés usando', 5),
(8, 'Recoger basura', 'Recoge al menos un residuo que encuentres en la calle', 15),
(9, 'Usar botella reutilizable', 'Evita comprar botellas de plástico hoy', 10),
(10, 'Plantar una planta', 'Planta una semilla o cuida una planta', 20),
(11, 'Comer local', 'Consume alimentos producidos localmente', 10),
(12, 'Reducir carne', 'Evita comer carne hoy para reducir huella ecológica', 15),
(13, 'Apagar dispositivos', 'Apaga dispositivos electrónicos que no uses', 5),
(14, 'Ir caminando', 'Camina en lugar de usar transporte contaminante', 15),
(15, 'Usar luz natural', 'Aprovecha la luz del sol en lugar de encender lámparas', 5),
(16, 'Ducha corta', 'Dúchate en menos de 5 minutos', 10),
(17, 'Reutilizar papel', 'Usa las dos caras del papel antes de tirarlo', 5),
(18, 'Comprar a granel', 'Compra productos sin envases innecesarios', 10),
(19, 'Regar plantas', 'Cuida las plantas o árboles cercanos', 5),
(20, 'Compartir transporte', 'Comparte coche o transporte con alguien', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `misiones_completadas`
--

CREATE TABLE `misiones_completadas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_mision` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `misiones_completadas`
--

INSERT INTO `misiones_completadas` (`id`, `id_usuario`, `id_mision`, `fecha`) VALUES
(1, 2, 4, '2026-03-09 16:23:00'),
(2, 2, 1, '2026-03-09 16:23:05'),
(3, 2, 3, '2026-03-09 17:23:51'),
(4, 2, 2, '2026-03-09 17:23:52'),
(5, 1, 4, '2026-03-09 17:43:50'),
(6, 1, 1, '2026-03-09 17:43:52'),
(7, 1, 16, '2026-03-09 18:02:11'),
(8, 1, 8, '2026-03-09 18:02:12'),
(9, 1, 14, '2026-03-09 18:02:13'),
(10, 1, 20, '2026-03-09 18:02:16'),
(11, 4, 12, '2026-03-10 08:45:20'),
(12, 4, 19, '2026-03-10 08:45:23'),
(13, 4, 2, '2026-03-10 08:51:47'),
(14, 4, 9, '2026-03-10 09:47:12'),
(15, 4, 16, '2026-03-10 09:47:22'),
(16, 4, 13, '2026-03-10 09:48:36'),
(17, 4, 4, '2026-03-10 09:49:17'),
(18, 4, 7, '2026-03-10 09:50:11'),
(19, 4, 14, '2026-03-10 09:50:37'),
(20, 4, 17, '2026-03-10 09:52:56'),
(21, 4, 8, '2026-03-10 09:53:11'),
(22, 4, 3, '2026-03-10 09:53:18'),
(23, 4, 20, '2026-03-10 09:56:29'),
(24, 4, 18, '2026-03-10 09:57:10'),
(25, 4, 1, '2026-03-10 09:57:18'),
(26, 4, 5, '2026-03-10 09:59:46'),
(27, 4, 11, '2026-03-10 09:59:52'),
(28, 4, 10, '2026-03-10 10:00:00'),
(29, 4, 6, '2026-03-10 10:00:09'),
(30, 4, 15, '2026-03-10 10:00:24'),
(31, 1, 5, '2026-03-10 10:01:10'),
(32, 1, 6, '2026-03-10 10:01:18'),
(33, 1, 4, '2026-03-10 10:01:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `fecha_registro`) VALUES
(1, 'Chema', 'chema.gesyfar@outlook.es', '$2y$10$e64pBL4krR9lXP1w6ypMXO.McQmiGvp.89wNAhOdgJtAcaNdonfla', '2026-03-09 08:55:28'),
(2, 'Luisito', 'prueba1@test.com', '$2y$10$/94T4Kman3NaiYsa4Kk8P.0usLWRdFVYhVLeJKZkmReeN/47lcHHS', '2026-03-09 12:18:57'),
(3, 'Kevin', 'kevin@gesyfar.com', '$2y$10$k8ltisECobBTgns50I.fG.QBvWRF9LtDtM89YGCpurfHbSyGGChiu', '2026-03-09 14:59:32'),
(4, 'Marta', 'marta@test.com', '$2y$10$Dl.45iAPQ69VTTHJNnf97.SI4Z8LPtpP.Xsshmb4M8nkm3.3CztJ6', '2026-03-10 08:44:02');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `entorno`
--
ALTER TABLE `entorno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `misiones`
--
ALTER TABLE `misiones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `misiones_completadas`
--
ALTER TABLE `misiones_completadas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_mision` (`id_mision`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `entorno`
--
ALTER TABLE `entorno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `misiones`
--
ALTER TABLE `misiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `misiones_completadas`
--
ALTER TABLE `misiones_completadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `entorno`
--
ALTER TABLE `entorno`
  ADD CONSTRAINT `entorno_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD CONSTRAINT `mascotas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `misiones_completadas`
--
ALTER TABLE `misiones_completadas`
  ADD CONSTRAINT `misiones_completadas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `misiones_completadas_ibfk_2` FOREIGN KEY (`id_mision`) REFERENCES `misiones` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
