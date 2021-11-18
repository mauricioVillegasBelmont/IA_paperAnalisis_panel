-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 01-09-2021 a las 18:22:57
-- Versión del servidor: 5.7.24
-- Versión de PHP: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `site_g73002`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `site_data`
--

CREATE TABLE `site_data` (
  `site_data_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  `status` tinyint(4) NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(32) DEFAULT NULL,
  `lastname` varchar(32) DEFAULT NULL,
  `user` varchar(32) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `pwd` varchar(64) DEFAULT NULL,
  `salt` binary(64) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`user_id`, `name`, `lastname`, `user`, `email`, `pwd`, `salt`, `level`, `created`, `last_login`, `active`) VALUES
(1, 'Angel', 'Trejo', 'root', 'ing.link.316@gmail.com', '039b1a63d4b74137adb6555397fb02462b114f4c46b8f47dba8e48eff67136ca', 0xf130a9c6ac02676b9d75f8ae5e2d68ba5f0e265d9edc7e5d77b1cb6b77b8aee591ad7be221a1bb4b39950e585cb548475e2f855307078e73eb4d2962f33258e0, 1, NULL, '2021-09-01 12:52:56', 1),
(2, 'Test', 'qwe', 'develop', 'desarrollo@losdeidea.com.mx', '6eb32b2ea22aaa3bcdea24a6023b7eb6395f2a6f4bdb3ef0dbe028d6ec69e0ff', 0x364ad8fd4749a8708711d6195ce404afd8d9e026241d99b87d0d5a23b4a35927aaf6b886dc80c434b0401426f91282f6f14f9521b02e97ac170f8f6429259aa8, 2, '2021-07-20 01:02:39', '2021-07-23 17:55:25', 1);

--
-- Índices para tablas volcadas
--

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
-- AUTO_INCREMENT de la tabla `site_data`
--
ALTER TABLE `site_data`
  MODIFY `site_data_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
