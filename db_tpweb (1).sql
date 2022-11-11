-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-11-2022 a las 20:15:51
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_tpweb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_admin`
--

CREATE TABLE `tb_admin` (
  `nombre_usuario` varchar(30) NOT NULL,
  `contrasenia` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tb_admin`
--

INSERT INTO `tb_admin` (`nombre_usuario`, `contrasenia`) VALUES
('admin', '$2a$12$YWklT0Vpf0MkOgvU2ia4o.jLH2XkB3/hbVyJ0OXU.BnBfwo7f7xza');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_propiedad`
--

CREATE TABLE `tb_propiedad` (
  `id` int(11) NOT NULL,
  `titulo` varchar(130) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `operacion` varchar(20) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` double NOT NULL,
  `metros_cuadrados` double NOT NULL,
  `ambientes` tinyint(4) NOT NULL,
  `banios` tinyint(4) NOT NULL,
  `permite_mascotas` bit(1) NOT NULL,
  `propietario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tb_propiedad`
--

INSERT INTO `tb_propiedad` (`id`, `titulo`, `tipo`, `operacion`, `descripcion`, `precio`, `metros_cuadrados`, `ambientes`, `banios`, `permite_mascotas`, `propietario`) VALUES
(13, 'titulo casa 20', 'casa', 'venta', 'descripcion de la casa 2', 5000, 1600, 7, 2, b'0', 16345443),
(41, 'Casa con mucha luz natural', 'casa', 'alquiler', 'lorem ipsum amet generet ap sured', 150800, 40, 2, 1, b'1', 16345443),
(42, 'Departamento zona centro', 'departamento', 'venta', 'Descripcion del departamento', 70200, 66, 3, 1, b'1', 34567897);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_propietario`
--

CREATE TABLE `tb_propietario` (
  `dni` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `telefono` varchar(25) NOT NULL,
  `mail` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tb_propietario`
--

INSERT INTO `tb_propietario` (`dni`, `nombre`, `apellido`, `telefono`, `mail`) VALUES
(16345443, 'ricardo', 'rodriguez', '4434-322344', 'ricardorodriguez@hotmail.com'),
(34567897, 'Ricardo', 'Torres', '2262556677', 'elTorres@hotmail.com');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`nombre_usuario`);

--
-- Indices de la tabla `tb_propiedad`
--
ALTER TABLE `tb_propiedad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `propietario` (`propietario`);

--
-- Indices de la tabla `tb_propietario`
--
ALTER TABLE `tb_propietario`
  ADD PRIMARY KEY (`dni`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tb_propiedad`
--
ALTER TABLE `tb_propiedad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tb_propiedad`
--
ALTER TABLE `tb_propiedad`
  ADD CONSTRAINT `tb_propiedad_ibfk_1` FOREIGN KEY (`propietario`) REFERENCES `tb_propietario` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
