-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 29-01-2021 a las 14:00:29
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cpc`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditorias`
--

CREATE TABLE `auditorias` (
  `id` text NOT NULL,
  `auditor` text NOT NULL,
  `num_operario` text NOT NULL,
  `seguridad` text NOT NULL,
  `medioambiente` text NOT NULL,
  `calidad` text NOT NULL,
  `propuestas` text NOT NULL,
  `acciones` text NOT NULL,
  `comentarios` text NOT NULL,
  `fecha` text NOT NULL,
  `hora` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ausencias_rot`
--

CREATE TABLE `ausencias_rot` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha` text NOT NULL,
  `num_operario` text NOT NULL,
  `ausente` text NOT NULL,
  `causa` text NOT NULL,
  `puesto1` text NOT NULL,
  `puesto2` text NOT NULL,
  `horas1` int(11) NOT NULL,
  `horas2` int(11) NOT NULL,
  `turno` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ausencias_rot`
--

INSERT INTO `ausencias_rot` (`id`, `fecha`, `num_operario`, `ausente`, `causa`, `puesto1`, `puesto2`, `horas1`, `horas2`, `turno`) VALUES
(58, '2021-01-29', '1', 'true', 'Se encuentra mal', '', '', 0, 0, '12'),
(59, '2021-01-29', '4', 'true', 'Muro', '', '', 0, 0, '12'),
(60, '2021-01-29', '8', 'false', '', '8', '', 6, 0, '13'),
(61, '2021-01-29', '13', 'false', '', '11', '', 12, 0, '13'),
(62, '2021-01-29', '1', 'true', 'baja', '', '', 0, 0, '13'),
(63, '2021-01-29', '9', 'false', '', '4', '4', 6, 6, '13'),
(64, '2021-01-29', '11', 'false', '', '4', '', 8, 0, '13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidencias`
--

CREATE TABLE `incidencias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha` text NOT NULL,
  `turno` text NOT NULL,
  `num_operario` text NOT NULL,
  `operario` text NOT NULL,
  `puesto` text NOT NULL,
  `incidencia` text NOT NULL,
  `causa` text NOT NULL,
  `hora` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `incidencias`
--

INSERT INTO `incidencias` (`id`, `fecha`, `turno`, `num_operario`, `operario`, `puesto`, `incidencia`, `causa`, `hora`) VALUES
(1, '2021-01-25', '1', '2002', 'Ruben', 'Tonto Profesional', 'Se murio', 'Comediavirus', '28:88');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `num_operario` int(10) DEFAULT NULL,
  `turno` varchar(7) DEFAULT NULL,
  `nombre` varchar(31) DEFAULT NULL,
  `telefono` varchar(11) DEFAULT NULL,
  `id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`num_operario`, `turno`, `nombre`, `telefono`, `id`) VALUES
(2001, '', 'Augusto Oliveira Gomes', '663 060 213', 1),
(2002, 'TC', 'Jaime Manuel González Gómez', '663 064 211', 2),
(2003, '', 'Herminio Domínguez Fernández', '619 240 933', 3),
(2004, 'TC', 'Juan Ramón Lozano Rosiña', '640 884 493', 4),
(2005, '', 'Antonio Dopico González', '', 5),
(2006, '', 'David Solla Torrón', '605 134 008', 6),
(2007, 'TC', 'Jose de Sousa Orides', '621 041 065', 7),
(2008, '', 'Ángel Vidal Polo', '', 8),
(2009, '', 'Antonio Domínguez Pintos', '', 9),
(2011, 'TB', 'Manuel Ángel Rodríguez Lorenzo', '627 505 561', 10),
(2012, 'TD', 'Antonio Lomba Alonso', '629 661 014', 11),
(2013, 'TB', 'Javier Estévez Fernández', '666 184 066', 12),
(2014, 'TB', 'José Manuel Blanco Fernández', '607 242 320', 13),
(2015, 'TC', 'Enrique Costas Rodríguez', '622 624 140', 14),
(2016, '', 'Francisco Javier Sainza Díez', '', 15),
(2017, '', 'Miguel Ángel Alvárez Cardoso', '', 16),
(2018, 'TB', 'José Luis Guisande González', '649 885 949', 17),
(2019, '', 'Ángel Vaqueiro González', '639 327 495', 18),
(2020, '', 'Jacobo Pastoriza Rodríguez', '', 19),
(2021, 'TA', 'Diego Costal Pérez', '628 724 790', 20),
(2022, '', 'Rubén Alonso García', '616 486 363', 21),
(2023, 'TB', 'José Manuel Boubeta Graña', '647 929 043', 22),
(2024, 'TA', 'José Manuel Díaz Simil', '686 979 576', 23),
(2026, 'CENTRAL', 'Marcos Alfonso Martín', '627 558 476', 24),
(2028, 'TC', 'Francisco José Alonso Rodríguez', '651 919 841', 25),
(2029, 'TD', 'Rubén Bendaña Couse', '666 644 932', 26),
(2031, 'TC', 'Ramón Monzón Vidal', '644 743 744', 27),
(2032, 'TA', 'Iván Novoa Alonso', '688 293 611', 28),
(2034, 'TB', 'Antonio Pena Rodríguez', '670 559 908', 29),
(2035, 'TC', 'Alberto Lorenzo Estevez', '666 720 929', 30),
(2037, '', 'Manuel Amoedo Pereira', '', 31),
(2038, 'TC', 'Gustavo Martínez Amoedo', '', 32),
(2039, 'TB', 'Marcos Martínez Martínez', '661 994 259', 33),
(2040, 'TB', 'Francisco Seijas Vicente', '659 104 925', 34),
(2041, 'TB', 'Rafael Rodriguez Senra', '607 919 358', 35),
(2043, '', 'Alberto González Carballeira', '', 36),
(2045, 'TA', 'José Benito Grande Carro', '676 568 920', 37),
(2046, 'TA', 'Jesús Alonso Rodas', '615 758 391', 38),
(2049, '', 'Jorge Campos Portela', '658 206 764', 39),
(2050, 'TC', 'José Manuel Souto González', '689 385 246', 40),
(2054, 'TA', 'Marcos Souto Castro', '609 685 043', 41),
(2055, 'TA', 'Basilio Domínguez Comesaña', '698 156 368', 42),
(2056, '', 'José Ángel Rodríguez Alejos', '633 210 303', 43),
(2057, 'TD', 'Arturo Denis Barreiro', '686 684 120', 44),
(2058, 'TA', 'Henry Carreño', '667 528 073', 45),
(2059, 'TC', 'Diego Pastoriza Villanueva', '622 747 395', 46),
(2060, 'TD', 'Pedro Pombo Fernández', '604 027 765', 47),
(2061, '', 'José Lorenzo Gómez', '', 48),
(2062, 'TC', 'Guillermo Fernández Lorenzo', '661 025 103', 49),
(2063, '', 'Leonor Rodriguez Moreira', '', 50),
(2064, 'TA', 'Marcos Calo Alonso', '637 526 208', 51),
(2065, 'TC', 'Adrián Giráldez Giráldez', '628 864 969', 52),
(2066, '', 'Ramón Conde González', '', 53),
(2067, 'TB', 'Luis Alberto Álvarez Vicente', '661 418 792', 54);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puestos`
--

CREATE TABLE `puestos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `puestos`
--

INSERT INTO `puestos` (`id`, `nombre`) VALUES
(1, 'Flejado'),
(2, 'Picking PA'),
(3, 'Picking PB'),
(4, 'Picking Central'),
(5, 'Transpaleta W428'),
(6, 'Transpaleta EMP2V2'),
(7, 'Transpaleta Laser'),
(8, 'Operario Laser'),
(9, 'Soldadura PA M3'),
(10, 'Macropulido'),
(11, 'Monitor'),
(12, 'RU');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha` text NOT NULL,
  `hora` text NOT NULL,
  `turno` text NOT NULL,
  `num_operario` text NOT NULL,
  `operario` text NOT NULL,
  `puesto` text NOT NULL,
  `reporte` text NOT NULL,
  `comentario` text NOT NULL,
  `coste` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `reportes`
--

INSERT INTO `reportes` (`id`, `fecha`, `hora`, `turno`, `num_operario`, `operario`, `puesto`, `reporte`, `comentario`, `coste`) VALUES
(1, '2021-01-26', '', '1', '2002', 'ruben', 'tonto profesional', 'troll', 'hey muy buenas a todos aquí willi', '288eypos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `encargado` text NOT NULL,
  `unix` text NOT NULL,
  `estado` text NOT NULL,
  `tipo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id`, `encargado`, `unix`, `estado`, `tipo`) VALUES
(11, '1', '1611746799', 'cerrado', 'TC'),
(12, '1', '1611764177', 'cerrado', 'TA'),
(13, '1', '1611920890', 'abierto', 'TD');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user` text NOT NULL,
  `mail` text NOT NULL,
  `clave` text NOT NULL,
  `last_login` text NOT NULL,
  `nombre` text NOT NULL,
  `num_operario` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `user`, `mail`, `clave`, `last_login`, `nombre`, `num_operario`) VALUES
(1, 'ruben', 'rbcilunion@gmail.com', '$2y$10$czWredIUAsMSbRB9MzYG6egzYBHobEVVUAf8gM.01FvyOlZyJj3r2', '1611923990', 'Rubén Bendaña', '2029'),
(2, 'ejemplo', 'ejemplo@gmail.com', '$2y$10$9CoHuI24SRuywrr.mk81neihH.wGwZpJrIOSeIeumRn2DIOHI7N0e', '1611741615', 'Ejemplo', '2002');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ausencias_rot`
--
ALTER TABLE `ausencias_rot`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `puestos`
--
ALTER TABLE `puestos`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ausencias_rot`
--
ALTER TABLE `ausencias_rot`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `puestos`
--
ALTER TABLE `puestos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
