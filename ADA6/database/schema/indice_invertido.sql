-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-10-2024 a las 21:09:38
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `indice_invertido`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `name_doc` varchar(150) NOT NULL,
  `creation_date` date NOT NULL,
  `url_doc` varchar(500) NOT NULL,
  `description_doc` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `position`
--

CREATE TABLE `position` (
  `id` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `id_term` int(11) NOT NULL,
  `id_doc` int(11) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postings`
--

CREATE TABLE `postings` (
  `id` int(11) NOT NULL,
  `id_doc` int(11) NOT NULL,
  `id_term` int(11) NOT NULL,
  `frequency` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vocabulary`
--

CREATE TABLE `vocabulary` (
  `id` int(11) NOT NULL,
  `terms` varchar(30) NOT NULL,
  `num_docs` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_doc` (`id_doc`),
  ADD KEY `id_post` (`id_post`),
  ADD KEY `id_term` (`id_term`);

--
-- Indices de la tabla `postings`
--
ALTER TABLE `postings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_doc` (`id_doc`),
  ADD KEY `id_term` (`id_term`);

--
-- Indices de la tabla `vocabulary`
--
ALTER TABLE `vocabulary`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `position`
--
ALTER TABLE `position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `postings`
--
ALTER TABLE `postings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `vocabulary`
--
ALTER TABLE `vocabulary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `position`
--
ALTER TABLE `position`
  ADD CONSTRAINT `position_ibfk_1` FOREIGN KEY (`id_doc`) REFERENCES `documents` (`id`),
  ADD CONSTRAINT `position_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `postings` (`id`),
  ADD CONSTRAINT `position_ibfk_3` FOREIGN KEY (`id_term`) REFERENCES `vocabulary` (`id`);

--
-- Filtros para la tabla `postings`
--
ALTER TABLE `postings`
  ADD CONSTRAINT `postings_ibfk_1` FOREIGN KEY (`id_doc`) REFERENCES `documents` (`id`),
  ADD CONSTRAINT `postings_ibfk_2` FOREIGN KEY (`id_term`) REFERENCES `vocabulary` (`id`);
COMMIT;

--FULLTEXT
ALTER TABLE documents ADD COLUMN texto_archivo TEXT;
ALTER TABLE documents ADD FULLTEXT (texto_archivo);
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
