-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 25-10-2019 a las 11:39:37
-- Versión del servidor: 5.7.26
-- Versión de PHP: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sensorchart`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_sensor`
--

DROP TABLE IF EXISTS `tb_sensor`;
CREATE TABLE IF NOT EXISTS `tb_sensor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tiempo` time NOT NULL,
  `mq135` int(11) NOT NULL,
  `mq9` int(11) NOT NULL,
  `mq2` int(11) NOT NULL,
  `mq5` int(11) NOT NULL,
  `fechalectura` date NOT NULL,
  `fechacarga` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tb_sensor`
--

INSERT INTO `tb_sensor` (`id`, `tiempo`, `mq135`, `mq9`, `mq2`, `mq5`, `fechalectura`, `fechacarga`) VALUES
(1, '09:00:00', 1, 0, 1, 2, '2019-10-21', '2019-10-21'),
(2, '08:00:00', 1, 2, 0, 0, '2019-10-22', '2019-10-22'),
(3, '09:00:00', 1, 3, 1, 2, '2019-10-23', '2019-10-23'),
(4, '05:00:00', 1, 1, 2, 2, '2019-10-24', '2019-10-24'),
(5, '04:00:00', 2, 2, 1, 1, '2019-10-25', '2019-10-25');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
