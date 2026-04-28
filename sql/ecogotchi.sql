-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 28-04-2026 a las 08:47:24
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.34

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
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_accion` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `id_usuario`, `id_accion`, `fecha`) VALUES
(1, 5, 7, '2026-04-27 16:04:38'),
(2, 5, 1, '2026-04-27 16:57:26'),
(3, 5, 3, '2026-04-27 17:43:29'),
(4, 5, 1, '2026-04-27 17:45:16'),
(5, 5, 1, '2026-04-27 17:53:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entorno`
--

CREATE TABLE `entorno` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nivel_ecologico` int(11) DEFAULT 50,
  `estado_entorno` varchar(30) DEFAULT 'normal',
  `fecha_ultima_actualizacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entorno`
--

INSERT INTO `entorno` (`id`, `id_usuario`, `nivel_ecologico`, `estado_entorno`, `fecha_ultima_actualizacion`) VALUES
(1, 1, 24, 'normal', '2026-03-18 11:20:23'),
(2, 3, 50, 'normal', '2026-03-10 10:56:40'),
(3, 2, 0, 'normal', '2026-03-18 11:21:19'),
(4, 4, 100, 'normal', '2026-03-10 10:56:40'),
(5, 5, 35, 'normal', '2026-04-28 08:45:43');

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
  `fecha_ultima_actualizacion` datetime DEFAULT current_timestamp(),
  `basura` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mascotas`
--

INSERT INTO `mascotas` (`id`, `id_usuario`, `nombre`, `tipo`, `color`, `hambre`, `sueno`, `diversion`, `higiene`, `salud`, `fecha_ultima_actualizacion`, `basura`) VALUES
(4, 3, 'Jorge', 'fantasia', 'azul', 16, 58, 16, 58, 100, '2026-03-09 15:43:52', 0),
(9, 4, 'Kevin', 'fantasia', 'verde', 60, 82, 60, 80, 100, '2026-03-10 09:59:38', 0),
(12, 1, 'Chema', 'planta', 'verde', 100, 100, 100, 100, 100, '2026-03-18 11:19:59', 0),
(16, 5, 'Luisito', 'planta', 'verde', 40, 40, 40, 40, 100, '2026-04-28 08:46:15', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `misiones`
--

CREATE TABLE `misiones` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `recompensa` int(11) DEFAULT NULL,
  `puntos_monedas` int(11) NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `misiones`
--

INSERT INTO `misiones` (`id`, `titulo`, `descripcion`, `recompensa`, `puntos_monedas`) VALUES
(1, 'Reciclar plástico', 'Hoy reciclaste una botella de plástico', 10, 10),
(2, 'Usar transporte público', 'Evita el coche hoy', 15, 20),
(3, 'Ahorrar agua', 'Reduce el tiempo en la ducha', 10, 10),
(4, 'Apagar luces', 'Apaga luces innecesarias', 5, 5),
(5, 'Reutilizar bolsa', 'Usa una bolsa reutilizable al hacer compras', 10, 10),
(6, 'Separar residuos', 'Separa papel, plástico y orgánico correctamente', 10, 15),
(7, 'Desconectar cargadores', 'Desconecta cargadores que no estés usando', 5, 5),
(8, 'Recoger basura', 'Recoge al menos un residuo que encuentres en la calle', 15, 20),
(9, 'Usar botella reutilizable', 'Evita comprar botellas de plástico hoy', 10, 10),
(10, 'Plantar una planta', 'Planta una semilla o cuida una planta', 20, 25),
(11, 'Comer local', 'Consume alimentos producidos localmente', 10, 10),
(12, 'Reducir carne', 'Evita comer carne hoy para reducir huella ecológica', 15, 15),
(13, 'Apagar dispositivos', 'Apaga dispositivos electrónicos que no uses', 5, 5),
(14, 'Ir caminando', 'Camina en lugar de usar transporte contaminante', 15, 15),
(15, 'Usar luz natural', 'Aprovecha la luz del sol en lugar de encender lámparas', 5, 5),
(16, 'Ducha corta', 'Dúchate en menos de 5 minutos', 10, 10),
(17, 'Reutilizar papel', 'Usa las dos caras del papel antes de tirarlo', 5, 5),
(18, 'Comprar a granel', 'Compra productos sin envases innecesarios', 10, 10),
(19, 'Regar plantas', 'Cuida las plantas o árboles cercanos', 5, 5),
(20, 'Compartir transporte', 'Comparte coche o transporte con alguien', 15, 15);

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
(33, 1, 4, '2026-03-10 10:01:25'),
(34, 1, 9, '2026-03-18 11:20:06'),
(35, 1, 14, '2026-03-18 11:20:15'),
(36, 5, 13, '2026-04-27 14:48:26'),
(37, 5, 8, '2026-04-27 14:48:40'),
(38, 5, 10, '2026-04-27 14:48:47'),
(39, 5, 18, '2026-04-27 14:48:55'),
(40, 5, 17, '2026-04-27 14:49:01'),
(41, 5, 3, '2026-04-27 16:03:56'),
(42, 5, 7, '2026-04-27 16:04:10'),
(43, 5, 2, '2026-04-27 16:04:16'),
(44, 5, 14, '2026-04-27 16:55:13'),
(45, 5, 5, '2026-04-27 17:43:12'),
(46, 5, 11, '2026-04-27 17:43:19'),
(47, 5, 20, '2026-04-27 17:43:24'),
(48, 5, 4, '2026-04-27 17:45:04'),
(49, 5, 6, '2026-04-27 17:45:12'),
(50, 5, 12, '2026-04-27 17:52:06'),
(51, 5, 16, '2026-04-27 17:52:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tienda_acciones`
--

CREATE TABLE `tienda_acciones` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `icono` varchar(10) DEFAULT '­ƒî▒',
  `coste` int(11) NOT NULL,
  `impacto_real` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tienda_acciones`
--

INSERT INTO `tienda_acciones` (`id`, `titulo`, `descripcion`, `icono`, `coste`, `impacto_real`) VALUES
(1, 'Punto de Reciclaje Barrio', 'Apoyas la instalación de un punto de reciclaje en tu vecindario para gestionar mejor los residuos.', '🌳', 25, 'Reduce hasta 300 kg de basura mezclada al año.'),
(2, 'Plantar un Roble Centenario', 'Contribuyes a la reforestación plantando un árbol que absorberá CO2 durante décadas.', '🌲', 40, 'Absorbe hasta 20kg de CO2 al año.'),
(3, 'Apoyar Bici-Escuela', 'Fomenta el transporte sostenible financiando clases de ciclismo urbano para jóvenes.', '🚴', 30, 'Ahorra aprox. 150kg de CO2 por alumno al año.'),
(7, 'Reforestar zona quemada', 'Ayudas a recuperar bosque perdido por incendios.', '🔥', 70, 'Por cada hectárea reforestada se fijan ~50 toneladas de CO2.'),
(8, 'Limpieza de Playas', 'Contribuyes a la recogida manual de pl??sticos y residuos en nuestras costas.', '🌊', 45, 'Retira 15kg de pl??sticos del mar.'),
(9, 'Energ??a Solar Escolar', 'Instalaci??n de paneles fotovoltaicos en colegios p??blicos para reducir emisiones.', '☀️', 80, 'Ahorra 1MWh de energ??a contaminante.'),
(10, 'Santuario de Abejas', 'Creaci??n de colmenas urbanas y siembra de flores silvestres para polinizadores.', '🐝', 35, 'Ayuda a polinizar 5.000 flores nuevas.'),
(11, 'Reforestar el Amazonas', 'Siembro de ??rboles aut??ctonos en zonas protegidas de la selva amaz??nica.', '🌴', 100, 'Captura 1 tonelada de CO2 en 10 a??os.'),
(12, 'Pozos de Agua Solar', 'Construcci??n de pozos con bombeo solar en zonas con escasez de agua.', '💧', 65, 'Suministra agua potable a 5 familias.'),
(13, 'Huerto Comunitario', 'Creaci??n de espacios de cultivo urbano sostenible para vecinos del barrio.', '🍅', 40, 'Produce 30kg de vegetales frescos locales.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `monedas_verdes` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `fecha_registro`, `monedas_verdes`) VALUES
(1, 'Chema', 'chema.gesyfar@outlook.es', '$2y$10$e64pBL4krR9lXP1w6ypMXO.McQmiGvp.89wNAhOdgJtAcaNdonfla', '2026-03-09 08:55:28', 0),
(2, 'Luisito', 'prueba1@test.com', '$2y$10$/94T4Kman3NaiYsa4Kk8P.0usLWRdFVYhVLeJKZkmReeN/47lcHHS', '2026-03-09 12:18:57', 0),
(3, 'Kevin', 'kevin@gesyfar.com', '$2y$10$k8ltisECobBTgns50I.fG.QBvWRF9LtDtM89YGCpurfHbSyGGChiu', '2026-03-09 14:59:32', 0),
(4, 'Marta', 'marta@test.com', '$2y$10$Dl.45iAPQ69VTTHJNnf97.SI4Z8LPtpP.Xsshmb4M8nkm3.3CztJ6', '2026-03-10 08:44:02', 0),
(5, 'Plantica', 'test23@test.com', '$2y$10$WjywbcQUsjHPD7J/NPAmouypi0wYFyuCMaOKo/C1MMDqD4wK1b/ze', '2026-04-27 14:38:23', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_accion` (`id_accion`);

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
-- Indices de la tabla `tienda_acciones`
--
ALTER TABLE `tienda_acciones`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `entorno`
--
ALTER TABLE `entorno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `misiones`
--
ALTER TABLE `misiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `misiones_completadas`
--
ALTER TABLE `misiones_completadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `tienda_acciones`
--
ALTER TABLE `tienda_acciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`id_accion`) REFERENCES `tienda_acciones` (`id`);

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
