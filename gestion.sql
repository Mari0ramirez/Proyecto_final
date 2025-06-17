-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-06-2025 a las 12:54:45
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
-- Base de datos: `srms`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `UserName` varchar(100) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `updationDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'valeri@gmail.com', '4b67deeb9aba04a5b54632ad19934f26', '2022-09-04 10:30:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblclasses`
--

CREATE TABLE `tblclasses` (
  `id` int(11) NOT NULL,
  `ClassName` varchar(80) DEFAULT NULL,
  `ClassNameNumeric` int(4) DEFAULT NULL,
  `Section` varchar(5) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tblclasses`
--

INSERT INTO `tblclasses` (`id`, `ClassName`, `ClassNameNumeric`, `Section`, `CreationDate`, `UpdationDate`) VALUES
(1, 'Primer Año', 2, 'A', '2022-09-04 08:31:45', NULL),
(64, 'Programación', 1, 'E', '2025-06-07 21:24:16', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblresult`
--

CREATE TABLE `tblresult` (
  `id` int(11) NOT NULL,
  `StudentId` int(11) DEFAULT NULL,
  `ClassId` int(11) DEFAULT NULL,
  `SubjectId` int(11) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tblresult`
--

INSERT INTO `tblresult` (`id`, `StudentId`, `ClassId`, `SubjectId`, `marks`, `PostingDate`, `UpdationDate`) VALUES
(1, 1, 1, 2, 89, '2022-09-04 08:41:18', NULL),
(2, 1, 1, 3, 87, '2022-09-04 08:41:18', NULL),
(3, 1, 1, 5, 66, '2022-09-04 08:41:18', NULL),
(4, 1, 1, 1, 78, '2022-09-04 08:41:18', NULL),
(5, 1, 1, 4, 90, '2022-09-04 08:41:18', NULL),
(6, 3, 1, 2, 80, '2022-09-04 09:56:54', NULL),
(7, 3, 1, 3, 66, '2022-09-04 09:56:54', NULL),
(8, 3, 1, 5, 87, '2022-09-04 09:56:54', NULL),
(9, 3, 1, 1, 76, '2022-09-04 09:56:54', NULL),
(10, 3, 1, 4, 55, '2022-09-04 09:56:54', NULL),
(11, 4, 1, 2, 90, '2022-10-19 21:21:47', NULL),
(12, 4, 1, 3, 70, '2022-10-19 21:21:47', NULL),
(13, 4, 1, 5, 80, '2022-10-19 21:21:47', NULL),
(14, 4, 1, 1, 80, '2022-10-19 21:21:47', NULL),
(15, 4, 1, 4, 74, '2022-10-19 21:21:47', NULL),
(16, 5, 63, 6, 98, '2022-10-21 22:29:10', NULL),
(17, 5, 63, 7, 97, '2022-10-21 22:29:10', NULL),
(18, 5, 63, 6, 98, '2022-10-21 22:29:42', NULL),
(19, 5, 63, 7, 97, '2022-10-21 22:29:42', NULL),
(20, 2, 1, 2, 90, '2025-06-07 22:16:38', NULL),
(21, 2, 1, 6, 98, '2025-06-07 22:16:38', NULL),
(22, 2, 1, 4, 67, '2025-06-07 22:16:38', NULL),
(23, 2, 1, 3, 89, '2025-06-07 22:16:38', NULL),
(24, 2, 1, 5, 63, '2025-06-07 22:16:38', NULL),
(25, 7, 64, 7, 487, '2025-06-07 22:19:55', NULL),
(26, 8, 64, 7, 98, '2025-06-07 22:25:11', NULL),
(27, 8, 64, 4, 89, '2025-06-07 22:27:44', NULL),
(28, 8, 64, 7, 98, '2025-06-07 22:27:44', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblstudents`
--

CREATE TABLE `tblstudents` (
  `StudentId` int(11) NOT NULL,
  `StudentName` varchar(100) DEFAULT NULL,
  `RollId` varchar(100) DEFAULT NULL,
  `StudentEmail` varchar(100) DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `DOB` varchar(100) DEFAULT NULL,
  `ClassId` int(11) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL,
  `Status` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tblstudents`
--

INSERT INTO `tblstudents` (`StudentId`, `StudentName`, `RollId`, `StudentEmail`, `Gender`, `DOB`, `ClassId`, `RegDate`, `UpdationDate`, `Status`) VALUES
(1, 'Juan Estudiante', '12125', 'jestudiante@cweb.com', 'Male', '1991-09-06', 1, '2022-09-04 08:38:05', NULL, 1),
(2, 'Patricia Cruz', '12124', 'pcruz@cweb.com', 'Female', '1992-08-31', 1, '2022-09-04 08:38:32', NULL, 1),
(3, 'Andrea Valencia', '12123', 'avalencia@cweb.com', 'Male', '1998-09-02', 1, '2022-09-04 09:56:15', NULL, 1),
(4, 'Pedro Estudiante 3', '12121', 'pestudiante@cweb.com', 'Male', '2000-06-06', 1, '2022-10-19 21:21:16', NULL, 1),
(5, 'Pedro Molina', '12122', 'pmolina@cweb.com', 'Male', '1999-06-16', 63, '2022-10-20 21:24:19', NULL, 1),
(6, 'Jose Andrade', '0025', 'jose@gmail.com', 'Male', '2021-06-07', 1, '2025-06-07 21:50:06', NULL, 1),
(7, 'Milnea tu amiga', '65', 'milena@gmail.com', 'Female', '2005-07-05', 64, '2025-06-07 21:52:02', NULL, 1),
(8, 'JUAN PEREZ', '1999', 'juanoprerez@gmail.com', 'Male', '2006-06-06', 64, '2025-06-07 22:24:15', NULL, 1),
(9, 'Mario', '2025', 'rg23003@ues.edu.sv', 'Male', '2000-10-06', 1, '2025-06-15 04:39:26', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblsubjectcombination`
--

CREATE TABLE `tblsubjectcombination` (
  `id` int(11) NOT NULL,
  `ClassId` int(11) DEFAULT NULL,
  `SubjectId` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `Updationdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tblsubjectcombination`
--

INSERT INTO `tblsubjectcombination` (`id`, `ClassId`, `SubjectId`, `status`, `CreationDate`, `Updationdate`) VALUES
(1, 1, 1, 1, '2022-09-04 08:37:16', '2022-10-21 14:57:29'),
(2, 1, 2, 1, '2022-09-04 08:40:16', NULL),
(3, 1, 3, 1, '2022-09-04 08:40:25', NULL),
(4, 1, 4, 1, '2022-09-04 08:40:32', '2025-06-07 21:44:58'),
(5, 1, 5, 1, '2022-09-04 08:40:40', NULL),
(6, 63, 6, 1, '2022-09-04 09:55:40', NULL),
(7, 63, 7, 1, '2022-10-21 22:25:52', NULL),
(8, 63, 7, 0, '2022-10-21 22:26:25', '2022-10-21 22:27:32'),
(9, 1, 6, 1, '2025-06-07 21:38:55', NULL),
(10, 64, 7, 1, '2025-06-07 21:39:04', NULL),
(11, 64, 4, 1, '2025-06-07 22:26:19', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblsubjects`
--

CREATE TABLE `tblsubjects` (
  `id` int(11) NOT NULL,
  `SubjectName` varchar(100) NOT NULL,
  `SubjectCode` varchar(100) DEFAULT NULL,
  `Creationdate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tblsubjects`
--

INSERT INTO `tblsubjects` (`id`, `SubjectName`, `SubjectCode`, `Creationdate`, `UpdationDate`) VALUES
(2, 'Ciencia de Datos Introducción', 'CDI', '2022-09-04 08:39:32', NULL),
(3, 'Inglés Conversacional', 'ICL', '2022-09-04 08:39:44', NULL),
(4, 'Domótica', 'DMT', '2022-09-04 08:39:53', NULL),
(5, 'Sistemas Operativos Avanzados', 'SOA', '2022-09-04 08:40:03', NULL),
(6, 'Diseño UX', 'DUX', '2022-09-04 09:55:25', NULL),
(7, 'Fonética y Morfología Aplicada', 'FFMA', '2022-10-21 22:25:02', NULL),
(8, 'Fonética y Morfología Aplicada', 'FFMA', '2022-10-21 22:25:24', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tblclasses`
--
ALTER TABLE `tblclasses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tblresult`
--
ALTER TABLE `tblresult`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`StudentId`);

--
-- Indices de la tabla `tblsubjectcombination`
--
ALTER TABLE `tblsubjectcombination`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tblsubjects`
--
ALTER TABLE `tblsubjects`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tblclasses`
--
ALTER TABLE `tblclasses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT de la tabla `tblresult`
--
ALTER TABLE `tblresult`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `tblstudents`
--
ALTER TABLE `tblstudents`
  MODIFY `StudentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tblsubjectcombination`
--
ALTER TABLE `tblsubjectcombination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `tblsubjects`
--
ALTER TABLE `tblsubjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
