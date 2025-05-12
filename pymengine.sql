-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 12-05-2025 a las 20:06:49
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
-- Estructura de tabla para la tabla `bibliography`
--

CREATE TABLE `bibliography` (
  `bibliography_id` int NOT NULL,
  `name` varchar(256) NOT NULL,
  `autors` varchar(255) DEFAULT NULL,
  `type` enum('libro','articulo_revista','articulo_periodico','fuente_web','tesis','informe','capitulo_libro','ley','norma','otros') NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `document_pdf`
--

CREATE TABLE `document_pdf` (
  `document_pdf_id` int NOT NULL,
  `name` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `processed` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `document_references`
--

CREATE TABLE `document_references` (
  `document_references_id` int NOT NULL,
  `document` int NOT NULL,
  `apa` varchar(3000) NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `label`
--

CREATE TABLE `label` (
  `label_id` int NOT NULL,
  `name` varchar(256) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paper`
--

CREATE TABLE `paper` (
  `paper_id` int NOT NULL,
  `title` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `authors` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `labels` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `abstract` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `metodology` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `conclusions` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `bibliography` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `document` int NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `path` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paper_to_bibliography`
--

CREATE TABLE `paper_to_bibliography` (
  `paper_to_bibliography_id` int NOT NULL,
  `paper` int NOT NULL,
  `bibliography` int NOT NULL,
  `document` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paper_to_label`
--

CREATE TABLE `paper_to_label` (
  `paper_to_label_id` int NOT NULL,
  `paper` int NOT NULL,
  `label` int NOT NULL,
  `document` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bibliography`
--
ALTER TABLE `bibliography`
  ADD PRIMARY KEY (`bibliography_id`);

--
-- Indices de la tabla `document_pdf`
--
ALTER TABLE `document_pdf`
  ADD PRIMARY KEY (`document_pdf_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `document_references`
--
ALTER TABLE `document_references`
  ADD PRIMARY KEY (`document_references_id`);

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
-- Indices de la tabla `paper_to_bibliography`
--
ALTER TABLE `paper_to_bibliography`
  ADD PRIMARY KEY (`paper_to_bibliography_id`);

--
-- Indices de la tabla `paper_to_label`
--
ALTER TABLE `paper_to_label`
  ADD PRIMARY KEY (`paper_to_label_id`);

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
-- AUTO_INCREMENT de la tabla `bibliography`
--
ALTER TABLE `bibliography`
  MODIFY `bibliography_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `document_pdf`
--
ALTER TABLE `document_pdf`
  MODIFY `document_pdf_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `document_references`
--
ALTER TABLE `document_references`
  MODIFY `document_references_id` int NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `paper_to_bibliography`
--
ALTER TABLE `paper_to_bibliography`
  MODIFY `paper_to_bibliography_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paper_to_label`
--
ALTER TABLE `paper_to_label`
  MODIFY `paper_to_label_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `site_data`
--
ALTER TABLE `site_data`
  MODIFY `site_data_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
