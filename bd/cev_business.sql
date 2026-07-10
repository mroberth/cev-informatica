-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 10-07-2026 a las 21:20:22
-- Versión del servidor: 8.4.3
-- Versión de PHP: 8.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cev_business`
--
CREATE DATABASE IF NOT EXISTS `cev_business` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cev_business`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciones_docentes`
--

CREATE TABLE `asignaciones_docentes` (
  `id` int NOT NULL,
  `id_seccion` int NOT NULL,
  `id_docente` int NOT NULL,
  `id_unidad_curricular` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asignaciones_docentes`
--

INSERT INTO `asignaciones_docentes` (`id`, `id_seccion`, `id_docente`, `id_unidad_curricular`) VALUES
(3, 1, 2, 2),
(4, 1, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id` int NOT NULL,
  `id_evaluacion` int NOT NULL,
  `id_estudiante` int NOT NULL,
  `nota` decimal(4,2) NOT NULL,
  `observaciones` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `especialidad` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id`, `id_usuario`, `especialidad`, `estado`) VALUES
(1, 8, 'Redes', 'Activo'),
(2, 9, 'Programación', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `estado_academico` enum('Activo','Egresado','Retirado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id`, `id_usuario`, `estado_academico`) VALUES
(1, 10, 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluaciones`
--

CREATE TABLE `evaluaciones` (
  `id` int NOT NULL,
  `id_asignacion` int NOT NULL,
  `descripcion` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `porcentaje` decimal(5,2) NOT NULL,
  `fecha_estimada` date DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fases`
--

CREATE TABLE `fases` (
  `id` int NOT NULL,
  `id_trayecto` int NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `fases`
--

INSERT INTO `fases` (`id`, `id_trayecto`, `nombre`, `descripcion`) VALUES
(1, 1, 'Fase 1', 'Primer semestre'),
(2, 2, 'Fase 1', 'Primer semestre'),
(3, 3, 'Fase 1', 'Primer semestre'),
(4, 4, 'Fase 1', 'Primer semestre'),
(5, 1, 'Fase 2', 'Segundo semestre'),
(6, 2, 'Fase 2', 'Segundo semestre'),
(7, 3, 'Fase 2', 'Segundo semestre'),
(8, 4, 'Fase 2', 'Segundo semestre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id` int NOT NULL,
  `id_estudiante` int NOT NULL,
  `id_seccion` int NOT NULL,
  `fecha_inscripcion` date NOT NULL,
  `estado` enum('Cursando','Retirado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Cursando',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`id`, `id_estudiante`, `id_seccion`, `fecha_inscripcion`, `estado`, `creado_en`, `actualizado_en`) VALUES
(1, 1, 1, '2026-07-10', 'Cursando', '2026-07-10 20:57:03', '2026-07-10 20:57:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos_academicos`
--

CREATE TABLE `periodos_academicos` (
  `id` int NOT NULL,
  `nombre` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Inactivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `periodos_academicos`
--

INSERT INTO `periodos_academicos` (`id`, `nombre`, `fecha_inicio`, `fecha_fin`, `estado`) VALUES
(1, '2026-I', '2026-03-01', '2026-07-31', 'Activo'),
(2, '2026-II', '2026-09-15', '2026-12-20', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones`
--

CREATE TABLE `secciones` (
  `id` int NOT NULL,
  `id_periodo` int NOT NULL,
  `id_trayecto` int NOT NULL,
  `codigo_seccion` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `turno` enum('Diurno','Nocturno','Fines de Semana') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `secciones`
--

INSERT INTO `secciones` (`id`, `id_periodo`, `id_trayecto`, `codigo_seccion`, `turno`) VALUES
(1, 1, 1, 'IN-1101', 'Diurno'),
(2, 1, 2, 'IN-2101', 'Diurno'),
(3, 1, 3, 'IN-3101', 'Diurno'),
(4, 1, 4, 'IN-4101', 'Diurno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trayectos`
--

CREATE TABLE `trayectos` (
  `id` int NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `trayectos`
--

INSERT INTO `trayectos` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Trayecto I', 'Primer año'),
(2, 'Trayecto II', 'Segundo año'),
(3, 'Trayecto III', 'Tercer año'),
(4, 'Trayecto IV', 'Cuarto año');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades_curriculares`
--

CREATE TABLE `unidades_curriculares` (
  `id` int NOT NULL,
  `codigo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unidades_credito` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `unidades_curriculares`
--

INSERT INTO `unidades_curriculares` (`id`, `codigo`, `nombre`, `unidades_credito`) VALUES
(1, 'MAT-01', 'Matematica I', 4),
(2, 'IDI-01', 'Idiomas-I', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_curricular_fases`
--

CREATE TABLE `unidad_curricular_fases` (
  `id_unidad_curricular` int NOT NULL,
  `id_fase` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `unidad_curricular_fases`
--

INSERT INTO `unidad_curricular_fases` (`id_unidad_curricular`, `id_fase`) VALUES
(1, 1),
(2, 1),
(1, 5),
(2, 5);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaciones_docentes`
--
ALTER TABLE `asignaciones_docentes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_asignacion` (`id_seccion`,`id_unidad_curricular`),
  ADD KEY `fk_asignacion_docente` (`id_docente`),
  ADD KEY `fk_asignacion_uc` (`id_unidad_curricular`);

--
-- Indices de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_calificacion` (`id_evaluacion`,`id_estudiante`),
  ADD KEY `fk_calificacion_estudiante` (`id_estudiante`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_evaluacion_asignacion` (`id_asignacion`);

--
-- Indices de la tabla `fases`
--
ALTER TABLE `fases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_fase_trayecto` (`id_trayecto`,`nombre`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_inscripcion` (`id_estudiante`,`id_seccion`),
  ADD KEY `fk_inscripcion_seccion` (`id_seccion`);

--
-- Indices de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `secciones`
--
ALTER TABLE `secciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_seccion_periodo` (`codigo_seccion`,`id_periodo`),
  ADD KEY `fk_seccion_periodo` (`id_periodo`),
  ADD KEY `fk_seccion_trayecto` (`id_trayecto`);

--
-- Indices de la tabla `trayectos`
--
ALTER TABLE `trayectos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `unidades_curriculares`
--
ALTER TABLE `unidades_curriculares`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `unidad_curricular_fases`
--
ALTER TABLE `unidad_curricular_fases`
  ADD PRIMARY KEY (`id_unidad_curricular`,`id_fase`),
  ADD KEY `id_fase` (`id_fase`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaciones_docentes`
--
ALTER TABLE `asignaciones_docentes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fases`
--
ALTER TABLE `fases`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `secciones`
--
ALTER TABLE `secciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `trayectos`
--
ALTER TABLE `trayectos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `unidades_curriculares`
--
ALTER TABLE `unidades_curriculares`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaciones_docentes`
--
ALTER TABLE `asignaciones_docentes`
  ADD CONSTRAINT `fk_asignacion_docente` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asignacion_seccion` FOREIGN KEY (`id_seccion`) REFERENCES `secciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asignacion_uc` FOREIGN KEY (`id_unidad_curricular`) REFERENCES `unidades_curriculares` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD CONSTRAINT `fk_calificacion_estudiante` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_calificacion_evaluacion` FOREIGN KEY (`id_evaluacion`) REFERENCES `evaluaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD CONSTRAINT `fk_evaluacion_asignacion` FOREIGN KEY (`id_asignacion`) REFERENCES `asignaciones_docentes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `fases`
--
ALTER TABLE `fases`
  ADD CONSTRAINT `fases_ibfk_1` FOREIGN KEY (`id_trayecto`) REFERENCES `trayectos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `fk_inscripcion_estudiante` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inscripcion_seccion` FOREIGN KEY (`id_seccion`) REFERENCES `secciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `secciones`
--
ALTER TABLE `secciones`
  ADD CONSTRAINT `fk_seccion_periodo` FOREIGN KEY (`id_periodo`) REFERENCES `periodos_academicos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_seccion_trayecto` FOREIGN KEY (`id_trayecto`) REFERENCES `trayectos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `unidad_curricular_fases`
--
ALTER TABLE `unidad_curricular_fases`
  ADD CONSTRAINT `unidad_curricular_fases_ibfk_1` FOREIGN KEY (`id_unidad_curricular`) REFERENCES `unidades_curriculares` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `unidad_curricular_fases_ibfk_2` FOREIGN KEY (`id_fase`) REFERENCES `fases` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
