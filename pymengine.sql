-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 10-05-2025 a las 01:58:57
-- Versión del servidor: 8.0.41-0ubuntu0.24.04.1
-- Versión de PHP: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `miau_tools`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `label`
--

CREATE TABLE `label` (
  `label_id` int NOT NULL,
  `name` varchar(256) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paper`
--

CREATE TABLE `paper` (
  `paper_id` int NOT NULL,
  `title` varchar(1024) NOT NULL,
  `authors` text NOT NULL,
  `labels` text NOT NULL,
  `abstract` text NOT NULL,
  `metodology` text NOT NULL,
  `conclusions` text NOT NULL,
  `bibliography` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `document` mediumtext NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `path` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reference`
--

CREATE TABLE `reference` (
  `reference_id` int NOT NULL,
  `name` varchar(256) NOT NULL,
  `autors` varchar(255) DEFAULT NULL,
  `type` enum('libro','articulo_revista','articulo_periodico','fuente_web','tesis','informe','capitulo_libro','ley','norma','otros') NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `site_data`
--

CREATE TABLE `site_data` (
  `site_data_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  `status` tinyint NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `user_id` int NOT NULL,
  `name` varchar(32) DEFAULT NULL,
  `lastname` varchar(32) DEFAULT NULL,
  `user` varchar(32) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `pwd` varchar(64) DEFAULT NULL,
  `salt` binary(64) DEFAULT NULL,
  `level` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`user_id`, `name`, `lastname`, `user`, `email`, `pwd`, `salt`, `level`, `created`, `last_login`, `active`) VALUES
(1, NULL, NULL, 'admin', NULL, 'f78707fa8c6113fee11fe09b39941df5fd6ae022e6214aaf70cbeb25647afccc', 0xc0363b272299065ba8d278910e87441f7f0ba5681193d36046c53ecc4fab88d1a0d868248e14e2f24654a4e7160e40160143c7b6010d1a037562169d83f6d314, 1, NULL, '2025-05-09 13:13:52', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `label`
--
ALTER TABLE `label`
  ADD PRIMARY KEY (`label_id`);

--
-- Indices de la tabla `paper`
--
ALTER TABLE `paper`
  ADD PRIMARY KEY (`paper_id`);

--
-- Indices de la tabla `reference`
--
ALTER TABLE `reference`
  ADD PRIMARY KEY (`reference_id`);

--
-- Indices de la tabla `site_data`
--
ALTER TABLE `site_data`
  ADD PRIMARY KEY (`site_data_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `label`
--
ALTER TABLE `label`
  MODIFY `label_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paper`
--
ALTER TABLE `paper`
  MODIFY `paper_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reference`
--
ALTER TABLE `reference`
  MODIFY `reference_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `site_data`
--
ALTER TABLE `site_data`
  MODIFY `site_data_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
