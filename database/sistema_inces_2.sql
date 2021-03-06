-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-06-2020 a las 13:18:47
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_inces`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `td_asignatura`
--

CREATE TABLE `td_asignatura` (
  `codigo` int(11) NOT NULL,
  `codigo_modulo` int(11) NOT NULL,
  `codigo_asignatura` int(11) NOT NULL,
  `nacionalidad_facilitador` char(1) DEFAULT NULL,
  `cedula_facilitador` varchar(12) DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `horas` int(11) NOT NULL,
  `dias_extras` int(11) NOT NULL,
  `seccion` int(11) NOT NULL,
  `turno` char(1) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `td_contacto`
--

CREATE TABLE `td_contacto` (
  `numero` int(11) NOT NULL,
  `rif` varchar(12) NOT NULL,
  `nacionalidad` char(1) NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `codigo_cargo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `td_facilitador_asig`
--

CREATE TABLE `td_facilitador_asig` (
  `codigo` int(11) NOT NULL,
  `codigo_asignatura` int(11) NOT NULL,
  `nacionalidad_f` char(1) NOT NULL,
  `cedula_f` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `td_modulo`
--

CREATE TABLE `td_modulo` (
  `codigo` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `codigo_oficio` int(11) NOT NULL,
  `codigo_modulo` int(11) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `td_rol_modulo`
--

CREATE TABLE `td_rol_modulo` (
  `codigo_rol` int(11) NOT NULL,
  `codigo_modulo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `td_rol_modulo`
--

INSERT INTO `td_rol_modulo` (`codigo_rol`, `codigo_modulo`) VALUES
(1, 1),
(1, 8),
(1, 7),
(1, 4),
(1, 5),
(1, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `td_rol_vista`
--

CREATE TABLE `td_rol_vista` (
  `codigo_rol` int(11) NOT NULL,
  `codigo_vista` int(11) NOT NULL,
  `registrar` tinyint(1) NOT NULL,
  `modificar` tinyint(1) NOT NULL,
  `act_desc` tinyint(1) NOT NULL,
  `eliminar` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `td_rol_vista`
--

INSERT INTO `td_rol_vista` (`codigo_rol`, `codigo_vista`, `registrar`, `modificar`, `act_desc`, `eliminar`) VALUES
(1, 1, 1, 1, 1, 1),
(1, 2, 1, 1, 1, 1),
(1, 19, 1, 1, 1, 1),
(1, 20, 1, 1, 1, 1),
(1, 7, 1, 1, 1, 1),
(1, 22, 1, 1, 1, 1),
(1, 9, 1, 1, 1, 1),
(1, 3, 1, 1, 1, 1),
(1, 6, 1, 1, 1, 1),
(1, 16, 1, 1, 1, 1),
(1, 17, 1, 1, 1, 1),
(1, 4, 1, 1, 1, 1),
(1, 5, 1, 1, 1, 1),
(1, 21, 1, 1, 1, 1),
(1, 10, 1, 1, 1, 1),
(1, 11, 1, 1, 1, 1),
(1, 12, 1, 1, 1, 1),
(1, 14, 1, 1, 1, 1),
(1, 15, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_actividad_economica`
--

CREATE TABLE `t_actividad_economica` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_asignatura`
--

CREATE TABLE `t_asignatura` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `t_asignatura`
--

INSERT INTO `t_asignatura` (`codigo`, `nombre`, `estatus`) VALUES
(1, 'Desarrollo del pensamiento', 'A'),
(2, 'Técnica para el aprendizaje efectivo', 'A'),
(3, 'ética', 'A'),
(4, 'Relaciones interpersonales', 'A'),
(5, 'Ecología', 'A'),
(6, 'Metodología de trabajo', 'A'),
(7, 'Educación de la sexualidad salud sexual y reproductiva', 'A'),
(8, 'Seguridad y higiene', 'A'),
(9, 'Equipo de oficina', 'A'),
(10, 'Administración de recursos humano', 'A'),
(11, 'Economía de materiales bienes y servicio s.', 'A'),
(12, 'Calculo mercantil', 'A'),
(13, 'Contabilidad', 'A'),
(14, 'Mercadeo y ventas de producto', 'A'),
(15, 'Organización de empresa', 'A'),
(16, 'Lenguaje de comunicación', 'A'),
(17, 'Informática', 'A'),
(18, 'Nociones básica de economía', 'A'),
(19, 'Inglés técnico', 'A'),
(20, 'Estadística aplicada', 'A'),
(21, 'Transmite aduanero', 'A'),
(22, 'Impuesto sobre la renta', 'A'),
(23, 'Deporte', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_asistencia`
--

CREATE TABLE `t_asistencia` (
  `numero` int(11) NOT NULL,
  `codigo_asignatura` int(11) NOT NULL,
  `numero_ficha` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `asistencia` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_bitacora`
--

CREATE TABLE `t_bitacora` (
  `numero` int(11) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `navegador` varchar(255) NOT NULL,
  `operacion` char(1) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'E'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_cargo`
--

CREATE TABLE `t_cargo` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_ciudad`
--

CREATE TABLE `t_ciudad` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(70) NOT NULL,
  `codigo_estado` int(11) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `t_ciudad`
--

INSERT INTO `t_ciudad` (`codigo`, `nombre`, `codigo_estado`, `estatus`) VALUES
(1, 'Maroa', 1, 'A'),
(2, 'Puerto Ayacucho', 1, 'A'),
(3, 'San Fernando de Atabapo', 1, 'A'),
(4, 'Anaco', 2, 'A'),
(5, 'Aragua de Barcelona', 2, 'A'),
(6, 'Barcelona', 2, 'A'),
(7, 'Boca de Uchire', 2, 'A'),
(8, 'Cantaura', 2, 'A'),
(9, 'Clarines', 2, 'A'),
(10, 'El Chaparro', 2, 'A'),
(11, 'El Pao Anzoátegui', 2, 'A'),
(12, 'El Tigre', 2, 'A'),
(13, 'El Tigrito', 2, 'A'),
(14, 'Guanape', 2, 'A'),
(15, 'Guanta', 2, 'A'),
(16, 'Lechería', 2, 'A'),
(17, 'Onoto', 2, 'A'),
(18, 'Pariaguán', 2, 'A'),
(19, 'Píritu', 2, 'A'),
(20, 'Puerto La Cruz', 2, 'A'),
(21, 'Puerto Píritu', 2, 'A'),
(22, 'Sabana de Uchire', 2, 'A'),
(23, 'San Mateo Anzoátegui', 2, 'A'),
(24, 'San Pablo Anzoátegui', 2, 'A'),
(25, 'San Tomé', 2, 'A'),
(26, 'Santa Ana de Anzoátegui', 2, 'A'),
(27, 'Santa Fe Anzoátegui', 2, 'A'),
(28, 'Santa Rosa', 2, 'A'),
(29, 'Soledad', 2, 'A'),
(30, 'Urica', 2, 'A'),
(31, 'Valle de Guanape', 2, 'A'),
(43, 'Achaguas', 3, 'A'),
(44, 'Biruaca', 3, 'A'),
(45, 'Bruzual', 3, 'A'),
(46, 'El Amparo', 3, 'A'),
(47, 'El Nula', 3, 'A'),
(48, 'Elorza', 3, 'A'),
(49, 'Guasdualito', 3, 'A'),
(50, 'Mantecal', 3, 'A'),
(51, 'Puerto Páez', 3, 'A'),
(52, 'San Fernando de Apure', 3, 'A'),
(53, 'San Juan de Payara', 3, 'A'),
(54, 'Barbacoas', 4, 'A'),
(55, 'Cagua', 4, 'A'),
(56, 'Camatagua', 4, 'A'),
(58, 'Choroní', 4, 'A'),
(59, 'Colonia Tovar', 4, 'A'),
(60, 'El Consejo', 4, 'A'),
(61, 'La Victoria', 4, 'A'),
(62, 'Las Tejerías', 4, 'A'),
(63, 'Magdaleno', 4, 'A'),
(64, 'Maracay', 4, 'A'),
(65, 'Ocumare de La Costa', 4, 'A'),
(66, 'Palo Negro', 4, 'A'),
(67, 'San Casimiro', 4, 'A'),
(68, 'San Mateo', 4, 'A'),
(69, 'San Sebastián', 4, 'A'),
(70, 'Santa Cruz de Aragua', 4, 'A'),
(71, 'Tocorón', 4, 'A'),
(72, 'Turmero', 4, 'A'),
(73, 'Villa de Cura', 4, 'A'),
(74, 'Zuata', 4, 'A'),
(75, 'Barinas', 5, 'A'),
(76, 'Barinitas', 5, 'A'),
(77, 'Barrancas', 5, 'A'),
(78, 'Calderas', 5, 'A'),
(79, 'Capitanejo', 5, 'A'),
(80, 'Ciudad Bolivia', 5, 'A'),
(81, 'El Cantón', 5, 'A'),
(82, 'Las Veguitas', 5, 'A'),
(83, 'Libertad de Barinas', 5, 'A'),
(84, 'Sabaneta', 5, 'A'),
(85, 'Santa Bárbara de Barinas', 5, 'A'),
(86, 'Socopó', 5, 'A'),
(87, 'Caicara del Orinoco', 6, 'A'),
(88, 'Canaima', 6, 'A'),
(89, 'Ciudad Bolívar', 6, 'A'),
(90, 'Ciudad Piar', 6, 'A'),
(91, 'El Callao', 6, 'A'),
(92, 'El Dorado', 6, 'A'),
(93, 'El Manteco', 6, 'A'),
(94, 'El Palmar', 6, 'A'),
(95, 'El Pao', 6, 'A'),
(96, 'Guasipati', 6, 'A'),
(97, 'Guri', 6, 'A'),
(98, 'La Paragua', 6, 'A'),
(99, 'Matanzas', 6, 'A'),
(100, 'Puerto Ordaz', 6, 'A'),
(101, 'San Félix', 6, 'A'),
(102, 'Santa Elena de Uairén', 6, 'A'),
(103, 'Tumeremo', 6, 'A'),
(104, 'Unare', 6, 'A'),
(105, 'Upata', 6, 'A'),
(106, 'Bejuma', 7, 'A'),
(107, 'Belén', 7, 'A'),
(108, 'Campo de Carabobo', 7, 'A'),
(109, 'Canoabo', 7, 'A'),
(110, 'Central Tacarigua', 7, 'A'),
(111, 'Chirgua', 7, 'A'),
(112, 'Ciudad Alianza', 7, 'A'),
(113, 'El Palito', 7, 'A'),
(114, 'Guacara', 7, 'A'),
(115, 'Guigue', 7, 'A'),
(116, 'Las Trincheras', 7, 'A'),
(117, 'Los Guayos', 7, 'A'),
(118, 'Mariara', 7, 'A'),
(119, 'Miranda', 7, 'A'),
(120, 'Montalbán', 7, 'A'),
(121, 'Morón', 7, 'A'),
(122, 'Naguanagua', 7, 'A'),
(123, 'Puerto Cabello', 7, 'A'),
(124, 'San Joaquín', 7, 'A'),
(125, 'Tocuyito', 7, 'A'),
(126, 'Urama', 7, 'A'),
(127, 'Valencia', 7, 'A'),
(128, 'Vigirimita', 7, 'A'),
(129, 'Aguirre', 8, 'A'),
(130, 'Apartaderos Cojedes', 8, 'A'),
(131, 'Arismendi', 8, 'A'),
(132, 'Camuriquito', 8, 'A'),
(133, 'El Baúl', 8, 'A'),
(134, 'El Limón', 8, 'A'),
(135, 'El Pao Cojedes', 8, 'A'),
(136, 'El Socorro', 8, 'A'),
(137, 'La Aguadita', 8, 'A'),
(138, 'Las Vegas', 8, 'A'),
(139, 'Libertad de Cojedes', 8, 'A'),
(140, 'Mapuey', 8, 'A'),
(141, 'Piñedo', 8, 'A'),
(142, 'Samancito', 8, 'A'),
(143, 'San Carlos', 8, 'A'),
(144, 'Sucre', 8, 'A'),
(145, 'Tinaco', 8, 'A'),
(146, 'Tinaquillo', 8, 'A'),
(147, 'Vallecito', 8, 'A'),
(148, 'Tucupita', 9, 'A'),
(149, 'Caracas', 24, 'A'),
(150, 'El Junquito', 24, 'A'),
(151, 'Adícora', 10, 'A'),
(152, 'Boca de Aroa', 10, 'A'),
(153, 'Cabure', 10, 'A'),
(154, 'Capadare', 10, 'A'),
(155, 'Capatárida', 10, 'A'),
(156, 'Chichiriviche', 10, 'A'),
(157, 'Churuguara', 10, 'A'),
(158, 'Coro', 10, 'A'),
(159, 'Cumarebo', 10, 'A'),
(160, 'Dabajuro', 10, 'A'),
(161, 'Judibana', 10, 'A'),
(162, 'La Cruz de Taratara', 10, 'A'),
(163, 'La Vela de Coro', 10, 'A'),
(164, 'Los Taques', 10, 'A'),
(165, 'Maparari', 10, 'A'),
(166, 'Mene de Mauroa', 10, 'A'),
(167, 'Mirimire', 10, 'A'),
(168, 'Pedregal', 10, 'A'),
(169, 'Píritu Falcón', 10, 'A'),
(170, 'Pueblo Nuevo Falcón', 10, 'A'),
(171, 'Puerto Cumarebo', 10, 'A'),
(172, 'Punta Cardón', 10, 'A'),
(173, 'Punto Fijo', 10, 'A'),
(174, 'San Juan de Los Cayos', 10, 'A'),
(175, 'San Luis', 10, 'A'),
(176, 'Santa Ana Falcón', 10, 'A'),
(177, 'Santa Cruz De Bucaral', 10, 'A'),
(178, 'Tocopero', 10, 'A'),
(179, 'Tocuyo de La Costa', 10, 'A'),
(180, 'Tucacas', 10, 'A'),
(181, 'Yaracal', 10, 'A'),
(182, 'Altagracia de Orituco', 11, 'A'),
(183, 'Cabruta', 11, 'A'),
(184, 'Calabozo', 11, 'A'),
(185, 'Camaguán', 11, 'A'),
(196, 'Chaguaramas Guárico', 11, 'A'),
(197, 'El Socorro', 11, 'A'),
(198, 'El Sombrero', 11, 'A'),
(199, 'Las Mercedes de Los Llanos', 11, 'A'),
(200, 'Lezama', 11, 'A'),
(201, 'Onoto', 11, 'A'),
(202, 'Ortíz', 11, 'A'),
(203, 'San José de Guaribe', 11, 'A'),
(204, 'San Juan de Los Morros', 11, 'A'),
(205, 'San Rafael de Laya', 11, 'A'),
(206, 'Santa María de Ipire', 11, 'A'),
(207, 'Tucupido', 11, 'A'),
(208, 'Valle de La Pascua', 11, 'A'),
(209, 'Zaraza', 11, 'A'),
(210, 'Aguada Grande', 12, 'A'),
(211, 'Atarigua', 12, 'A'),
(212, 'Barquisimeto', 12, 'A'),
(213, 'Bobare', 12, 'A'),
(214, 'Cabudare', 12, 'A'),
(215, 'Carora', 12, 'A'),
(216, 'Cubiro', 12, 'A'),
(217, 'Cují', 12, 'A'),
(218, 'Duaca', 12, 'A'),
(219, 'El Manzano', 12, 'A'),
(220, 'El Tocuyo', 12, 'A'),
(221, 'Guaríco', 12, 'A'),
(222, 'Humocaro Alto', 12, 'A'),
(223, 'Humocaro Bajo', 12, 'A'),
(224, 'La Miel', 12, 'A'),
(225, 'Moroturo', 12, 'A'),
(226, 'Quíbor', 12, 'A'),
(227, 'Río Claro', 12, 'A'),
(228, 'Sanare', 12, 'A'),
(229, 'Santa Inés', 12, 'A'),
(230, 'Sarare', 12, 'A'),
(231, 'Siquisique', 12, 'A'),
(232, 'Tintorero', 12, 'A'),
(233, 'Apartaderos Mérida', 13, 'A'),
(234, 'Arapuey', 13, 'A'),
(235, 'Bailadores', 13, 'A'),
(236, 'Caja Seca', 13, 'A'),
(237, 'Canaguá', 13, 'A'),
(238, 'Chachopo', 13, 'A'),
(239, 'Chiguara', 13, 'A'),
(240, 'Ejido', 13, 'A'),
(241, 'El Vigía', 13, 'A'),
(242, 'La Azulita', 13, 'A'),
(243, 'La Playa', 13, 'A'),
(244, 'Lagunillas Mérida', 13, 'A'),
(245, 'Mérida', 13, 'A'),
(246, 'Mesa de Bolívar', 13, 'A'),
(247, 'Mucuchíes', 13, 'A'),
(248, 'Mucujepe', 13, 'A'),
(249, 'Mucuruba', 13, 'A'),
(250, 'Nueva Bolivia', 13, 'A'),
(251, 'Palmarito', 13, 'A'),
(252, 'Pueblo Llano', 13, 'A'),
(253, 'Santa Cruz de Mora', 13, 'A'),
(254, 'Santa Elena de Arenales', 13, 'A'),
(255, 'Santo Domingo', 13, 'A'),
(256, 'Tabáy', 13, 'A'),
(257, 'Timotes', 13, 'A'),
(258, 'Torondoy', 13, 'A'),
(259, 'Tovar', 13, 'A'),
(260, 'Tucani', 13, 'A'),
(261, 'Zea', 13, 'A'),
(262, 'Araguita', 14, 'A'),
(263, 'Carrizal', 14, 'A'),
(264, 'Caucagua', 14, 'A'),
(265, 'Chaguaramas Miranda', 14, 'A'),
(266, 'Charallave', 14, 'A'),
(267, 'Chirimena', 14, 'A'),
(268, 'Chuspa', 14, 'A'),
(269, 'Cúa', 14, 'A'),
(270, 'Cupira', 14, 'A'),
(271, 'Curiepe', 14, 'A'),
(272, 'El Guapo', 14, 'A'),
(273, 'El Jarillo', 14, 'A'),
(274, 'Filas de Mariche', 14, 'A'),
(275, 'Guarenas', 14, 'A'),
(276, 'Guatire', 14, 'A'),
(277, 'Higuerote', 14, 'A'),
(278, 'Los Anaucos', 14, 'A'),
(279, 'Los Teques', 14, 'A'),
(280, 'Ocumare del Tuy', 14, 'A'),
(281, 'Panaquire', 14, 'A'),
(282, 'Paracotos', 14, 'A'),
(283, 'Río Chico', 14, 'A'),
(284, 'San Antonio de Los Altos', 14, 'A'),
(285, 'San Diego de Los Altos', 14, 'A'),
(286, 'San Fernando del Guapo', 14, 'A'),
(287, 'San Francisco de Yare', 14, 'A'),
(288, 'San José de Los Altos', 14, 'A'),
(289, 'San José de Río Chico', 14, 'A'),
(290, 'San Pedro de Los Altos', 14, 'A'),
(291, 'Santa Lucía', 14, 'A'),
(292, 'Santa Teresa', 14, 'A'),
(293, 'Tacarigua de La Laguna', 14, 'A'),
(294, 'Tacarigua de Mamporal', 14, 'A'),
(295, 'Tácata', 14, 'A'),
(296, 'Turumo', 14, 'A'),
(297, 'Aguasay', 15, 'A'),
(298, 'Aragua de Maturín', 15, 'A'),
(299, 'Barrancas del Orinoco', 15, 'A'),
(300, 'Caicara de Maturín', 15, 'A'),
(301, 'Caripe', 15, 'A'),
(302, 'Caripito', 15, 'A'),
(303, 'Chaguaramal', 15, 'A'),
(305, 'Chaguaramas Monagas', 15, 'A'),
(307, 'El Furrial', 15, 'A'),
(308, 'El Tejero', 15, 'A'),
(309, 'Jusepín', 15, 'A'),
(310, 'La Toscana', 15, 'A'),
(311, 'Maturín', 15, 'A'),
(312, 'Miraflores', 15, 'A'),
(313, 'Punta de Mata', 15, 'A'),
(314, 'Quiriquire', 15, 'A'),
(315, 'San Antonio de Maturín', 15, 'A'),
(316, 'San Vicente Monagas', 15, 'A'),
(317, 'Santa Bárbara', 15, 'A'),
(318, 'Temblador', 15, 'A'),
(319, 'Teresen', 15, 'A'),
(320, 'Uracoa', 15, 'A'),
(321, 'Altagracia', 16, 'A'),
(322, 'Boca de Pozo', 16, 'A'),
(323, 'Boca de Río', 16, 'A'),
(324, 'El Espinal', 16, 'A'),
(325, 'El Valle del Espíritu Santo', 16, 'A'),
(326, 'El Yaque', 16, 'A'),
(327, 'Juangriego', 16, 'A'),
(328, 'La Asunción', 16, 'A'),
(329, 'La Guardia', 16, 'A'),
(330, 'Pampatar', 16, 'A'),
(331, 'Porlamar', 16, 'A'),
(332, 'Puerto Fermín', 16, 'A'),
(333, 'Punta de Piedras', 16, 'A'),
(334, 'San Francisco de Macanao', 16, 'A'),
(335, 'San Juan Bautista', 16, 'A'),
(336, 'San Pedro de Coche', 16, 'A'),
(337, 'Santa Ana de Nueva Esparta', 16, 'A'),
(338, 'Villa Rosa', 16, 'A'),
(339, 'Acarigua', 17, 'A'),
(340, 'Agua Blanca', 17, 'A'),
(341, 'Araure', 17, 'A'),
(342, 'Biscucuy', 17, 'A'),
(343, 'Boconoito', 17, 'A'),
(344, 'Campo Elías', 17, 'A'),
(345, 'Chabasquén', 17, 'A'),
(346, 'Guanare', 17, 'A'),
(347, 'Guanarito', 17, 'A'),
(348, 'La Aparición', 17, 'A'),
(349, 'La Misión', 17, 'A'),
(350, 'Mesa de Cavacas', 17, 'A'),
(351, 'Ospino', 17, 'A'),
(352, 'Papelón', 17, 'A'),
(353, 'Payara', 17, 'A'),
(354, 'Pimpinela', 17, 'A'),
(355, 'Píritu de Portuguesa', 17, 'A'),
(356, 'San Rafael de Onoto', 17, 'A'),
(357, 'Santa Rosalía', 17, 'A'),
(358, 'Turén', 17, 'A'),
(359, 'Altos de Sucre', 18, 'A'),
(360, 'Araya', 18, 'A'),
(361, 'Cariaco', 18, 'A'),
(362, 'Carúpano', 18, 'A'),
(363, 'Casanay', 18, 'A'),
(364, 'Cumaná', 18, 'A'),
(365, 'Cumanacoa', 18, 'A'),
(366, 'El Morro Puerto Santo', 18, 'A'),
(367, 'El Pilar', 18, 'A'),
(368, 'El Poblado', 18, 'A'),
(369, 'Guaca', 18, 'A'),
(370, 'Guiria', 18, 'A'),
(371, 'Irapa', 18, 'A'),
(372, 'Manicuare', 18, 'A'),
(373, 'Mariguitar', 18, 'A'),
(374, 'Río Caribe', 18, 'A'),
(375, 'San Antonio del Golfo', 18, 'A'),
(376, 'San José de Aerocuar', 18, 'A'),
(377, 'San Vicente de Sucre', 18, 'A'),
(378, 'Santa Fe de Sucre', 18, 'A'),
(379, 'Tunapuy', 18, 'A'),
(380, 'Yaguaraparo', 18, 'A'),
(381, 'Yoco', 18, 'A'),
(382, 'Abejales', 19, 'A'),
(383, 'Borota', 19, 'A'),
(384, 'Bramon', 19, 'A'),
(385, 'Capacho', 19, 'A'),
(386, 'Colón', 19, 'A'),
(387, 'Coloncito', 19, 'A'),
(388, 'Cordero', 19, 'A'),
(389, 'El Cobre', 19, 'A'),
(390, 'El Pinal', 19, 'A'),
(391, 'Independencia', 19, 'A'),
(392, 'La Fría', 19, 'A'),
(393, 'La Grita', 19, 'A'),
(394, 'La Pedrera', 19, 'A'),
(395, 'La Tendida', 19, 'A'),
(396, 'Las Delicias', 19, 'A'),
(397, 'Las Hernández', 19, 'A'),
(398, 'Lobatera', 19, 'A'),
(399, 'Michelena', 19, 'A'),
(400, 'Palmira', 19, 'A'),
(401, 'Pregonero', 19, 'A'),
(402, 'Queniquea', 19, 'A'),
(403, 'Rubio', 19, 'A'),
(404, 'San Antonio del Tachira', 19, 'A'),
(405, 'San Cristobal', 19, 'A'),
(406, 'San José de Bolívar', 19, 'A'),
(407, 'San Josecito', 19, 'A'),
(408, 'San Pedro del Río', 19, 'A'),
(409, 'Santa Ana Táchira', 19, 'A'),
(410, 'Seboruco', 19, 'A'),
(411, 'Táriba', 19, 'A'),
(412, 'Umuquena', 19, 'A'),
(413, 'Ureña', 19, 'A'),
(414, 'Batatal', 20, 'A'),
(415, 'Betijoque', 20, 'A'),
(416, 'Boconó', 20, 'A'),
(417, 'Carache', 20, 'A'),
(418, 'Chejende', 20, 'A'),
(419, 'Cuicas', 20, 'A'),
(420, 'El Dividive', 20, 'A'),
(421, 'El Jaguito', 20, 'A'),
(422, 'Escuque', 20, 'A'),
(423, 'Isnotú', 20, 'A'),
(424, 'Jajó', 20, 'A'),
(425, 'La Ceiba', 20, 'A'),
(426, 'La Concepción de Trujllo', 20, 'A'),
(427, 'La Mesa de Esnujaque', 20, 'A'),
(428, 'La Puerta', 20, 'A'),
(429, 'La Quebrada', 20, 'A'),
(430, 'Mendoza Fría', 20, 'A'),
(431, 'Meseta de Chimpire', 20, 'A'),
(432, 'Monay', 20, 'A'),
(433, 'Motatán', 20, 'A'),
(434, 'Pampán', 20, 'A'),
(435, 'Pampanito', 20, 'A'),
(436, 'Sabana de Mendoza', 20, 'A'),
(437, 'San Lázaro', 20, 'A'),
(438, 'Santa Ana de Trujillo', 20, 'A'),
(439, 'Tostós', 20, 'A'),
(440, 'Trujillo', 20, 'A'),
(441, 'Valera', 20, 'A'),
(442, 'Carayaca', 21, 'A'),
(443, 'Litoral', 21, 'A'),
(444, 'Archipiélago Los Roques', 25, 'A'),
(445, 'Aroa', 22, 'A'),
(446, 'Boraure', 22, 'A'),
(447, 'Campo Elías de Yaracuy', 22, 'A'),
(448, 'Chivacoa', 22, 'A'),
(449, 'Cocorote', 22, 'A'),
(450, 'Farriar', 22, 'A'),
(451, 'Guama', 22, 'A'),
(452, 'Marín', 22, 'A'),
(453, 'Nirgua', 22, 'A'),
(454, 'Sabana de Parra', 22, 'A'),
(455, 'Salom', 22, 'A'),
(456, 'San Felipe', 22, 'A'),
(457, 'San Pablo de Yaracuy', 22, 'A'),
(458, 'Urachiche', 22, 'A'),
(459, 'Yaritagua', 22, 'A'),
(460, 'Yumare', 22, 'A'),
(461, 'Bachaquero', 23, 'A'),
(462, 'Bobures', 23, 'A'),
(463, 'Cabimas', 23, 'A'),
(464, 'Campo Concepción', 23, 'A'),
(465, 'Campo Mara', 23, 'A'),
(466, 'Campo Rojo', 23, 'A'),
(467, 'Carrasquero', 23, 'A'),
(468, 'Casigua', 23, 'A'),
(469, 'Chiquinquirá', 23, 'A'),
(470, 'Ciudad Ojeda', 23, 'A'),
(471, 'El Batey', 23, 'A'),
(472, 'El Carmelo', 23, 'A'),
(473, 'El Chivo', 23, 'A'),
(474, 'El Guayabo', 23, 'A'),
(475, 'El Mene', 23, 'A'),
(476, 'El Venado', 23, 'A'),
(477, 'Encontrados', 23, 'A'),
(478, 'Gibraltar', 23, 'A'),
(479, 'Isla de Toas', 23, 'A'),
(480, 'La Concepción del Zulia', 23, 'A'),
(481, 'La Paz', 23, 'A'),
(482, 'La Sierrita', 23, 'A'),
(483, 'Lagunillas del Zulia', 23, 'A'),
(484, 'Las Piedras de Perijá', 23, 'A'),
(485, 'Los Cortijos', 23, 'A'),
(486, 'Machiques', 23, 'A'),
(487, 'Maracaibo', 23, 'A'),
(488, 'Mene Grande', 23, 'A'),
(489, 'Palmarejo', 23, 'A'),
(490, 'Paraguaipoa', 23, 'A'),
(491, 'Potrerito', 23, 'A'),
(492, 'Pueblo Nuevo del Zulia', 23, 'A'),
(493, 'Puertos de Altagracia', 23, 'A'),
(494, 'Punta Gorda', 23, 'A'),
(495, 'Sabaneta de Palma', 23, 'A'),
(496, 'San Francisco', 23, 'A'),
(497, 'San José de Perijá', 23, 'A'),
(498, 'San Rafael del Moján', 23, 'A'),
(499, 'San Timoteo', 23, 'A'),
(500, 'Santa Bárbara Del Zulia', 23, 'A'),
(501, 'Santa Cruz de Mara', 23, 'A'),
(502, 'Santa Cruz del Zulia', 23, 'A'),
(503, 'Santa Rita', 23, 'A'),
(504, 'Sinamaica', 23, 'A'),
(505, 'Tamare', 23, 'A'),
(506, 'Tía Juana', 23, 'A'),
(507, 'Villa del Rosario', 23, 'A'),
(508, 'La Guaira', 21, 'A'),
(509, 'Catia La Mar', 21, 'A'),
(510, 'Macuto', 21, 'A'),
(511, 'Naiguatá', 21, 'A'),
(512, 'Archipiélago Los Monjes', 25, 'A'),
(513, 'Isla La Tortuga y Cayos adyacentes', 25, 'A'),
(514, 'Isla La Sola', 25, 'A'),
(515, 'Islas Los Testigos', 25, 'A'),
(516, 'Islas Los Frailes', 25, 'A'),
(517, 'Isla La Orchila', 25, 'A'),
(518, 'Archipiélago Las Aves', 25, 'A'),
(519, 'Isla de Aves', 25, 'A'),
(520, 'Isla La Blanquilla', 25, 'A'),
(521, 'Isla de Patos', 25, 'A'),
(522, 'Islas Los Hermanos', 25, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_contrasenas`
--

CREATE TABLE `t_contrasenas` (
  `numero` int(11) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `fecha` date NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_datos_hogar`
--

CREATE TABLE `t_datos_hogar` (
  `nacionalidad` char(1) NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `punto_referencia` varchar(200) NOT NULL,
  `tipo_area` char(1) NOT NULL,
  `tipo_vivienda` char(1) NOT NULL,
  `tenencia_vivienda` char(1) NOT NULL,
  `agua` char(1) NOT NULL,
  `electricidad` char(1) NOT NULL,
  `excretas` char(1) NOT NULL,
  `basura` char(1) NOT NULL,
  `otros` varchar(100) DEFAULT NULL,
  `techo` varchar(100) NOT NULL,
  `paredes` varchar(100) NOT NULL,
  `piso` varchar(100) NOT NULL,
  `via_acceso` varchar(100) NOT NULL,
  `sala` int(1) NOT NULL,
  `comedor` int(1) NOT NULL,
  `cocina` int(1) NOT NULL,
  `banos` int(1) NOT NULL,
  `n_dormitorios` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_datos_personales`
--

CREATE TABLE `t_datos_personales` (
  `nacionalidad` char(1) NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `nombre1` varchar(25) NOT NULL,
  `nombre2` varchar(25) DEFAULT NULL,
  `apellido1` varchar(25) NOT NULL,
  `apellido2` varchar(25) DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `fecha_n` date DEFAULT NULL,
  `mayor_edad` char(1) DEFAULT NULL,
  `codigo_ciudad_n` int(11) DEFAULT NULL,
  `lugar_n` varchar(100) DEFAULT NULL,
  `codigo_ocupacion` int(11) DEFAULT NULL,
  `estado_civil` char(1) DEFAULT NULL,
  `nivel_instruccion` char(2) DEFAULT NULL,
  `titulo_acade` varchar(100) DEFAULT NULL,
  `mision_participado` varchar(150) DEFAULT NULL,
  `codigo_ciudad` int(11) NOT NULL,
  `codigo_municipio` int(11) DEFAULT NULL,
  `codigo_parroquia` int(11) DEFAULT NULL,
  `direccion` varchar(200) NOT NULL DEFAULT 'S/D',
  `telefono1` char(11) NOT NULL,
  `telefono2` char(11) DEFAULT NULL,
  `correo` varchar(80) DEFAULT NULL,
  `tipo_persona` char(1) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `t_datos_personales`
--

INSERT INTO `t_datos_personales` (`nacionalidad`, `cedula`, `nombre1`, `nombre2`, `apellido1`, `apellido2`, `sexo`, `fecha_n`, `mayor_edad`, `codigo_ciudad_n`, `lugar_n`, `codigo_ocupacion`, `estado_civil`, `nivel_instruccion`, `titulo_acade`, `mision_participado`, `codigo_ciudad`, `codigo_municipio`, `codigo_parroquia`, `direccion`, `telefono1`, `telefono2`, `correo`, `tipo_persona`, `estatus`) VALUES
('V', '00000000', 'Nombre', NULL, 'Apellido', NULL, 'I', '0000-00-00', NULL, NULL, NULL, NULL, 'I', 'I', NULL, NULL, 1, NULL, NULL, 'Dirección', '00000000000', NULL, NULL, 'A', 'A'),
('V', '10147888', 'Juan', 'Jose', 'Perez', 'Jimenez', 'M', '1989-07-13', NULL, NULL, NULL, 3, 'S', 'BI', '', '', 339, NULL, 734, 'Urb. villa del pilar', '04120570111', '', '', 'F', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_documentos`
--

CREATE TABLE `t_documentos` (
  `numero_doc` int(11) NOT NULL,
  `nacionalidad` char(1) NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `entension` varchar(100) NOT NULL,
  `descripcion` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_empresa`
--

CREATE TABLE `t_empresa` (
  `rif` varchar(12) NOT NULL,
  `nil` varchar(10) DEFAULT NULL,
  `razon_social` varchar(150) NOT NULL,
  `codigo_actividad` int(11) NOT NULL,
  `codigo_aportante` char(12) DEFAULT NULL,
  `telefono1` char(11) NOT NULL,
  `telefono2` char(11) DEFAULT NULL,
  `correo` varchar(80) DEFAULT NULL,
  `codigo_ciudad` int(11) NOT NULL,
  `direccion` varchar(300) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_estado`
--

CREATE TABLE `t_estado` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `t_estado`
--

INSERT INTO `t_estado` (`codigo`, `nombre`, `estatus`) VALUES
(1, 'Amazonas', 'A'),
(2, 'Anzoátegui', 'A'),
(3, 'Apure', 'A'),
(4, 'Aragua', 'A'),
(5, 'Barinas', 'A'),
(6, 'Bolívar', 'A'),
(7, 'Carabobo', 'A'),
(8, 'Cojedes', 'A'),
(9, 'Delta Amacuro', 'A'),
(10, 'Falcón', 'A'),
(11, 'Guárico', 'A'),
(12, 'Lara', 'A'),
(13, 'Mérida', 'A'),
(14, 'Miranda', 'A'),
(15, 'Monagas', 'A'),
(16, 'Nueva Esparta', 'A'),
(17, 'Portuguesa', 'A'),
(18, 'Sucre', 'A'),
(19, 'Táchira', 'A'),
(20, 'Trujillo', 'A'),
(21, 'Vargas', 'A'),
(22, 'Yaracuy', 'A'),
(23, 'Zulia', 'A'),
(24, 'Distrito Capital', 'A'),
(25, 'Dependencias Federales', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_familia`
--

CREATE TABLE `t_familia` (
  `numero` int(11) NOT NULL,
  `nacionalidad` char(1) NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `nacionalidad_f` char(1) NOT NULL,
  `cedula_f` varchar(12) NOT NULL,
  `parentesco` char(2) NOT NULL,
  `trabaja` char(1) NOT NULL,
  `ingresos` float NOT NULL,
  `representante` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_ficha_aprendiz`
--

CREATE TABLE `t_ficha_aprendiz` (
  `numero` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `tipo_inscripcion` char(1) NOT NULL,
  `ficha_anterior` int(11) DEFAULT NULL,
  `correlativo` varchar(10) DEFAULT NULL,
  `numero_orden` varchar(3) NOT NULL,
  `numero_informe` int(11) NOT NULL,
  `empresa_actual` varchar(12) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'I'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_gestion_dinero`
--

CREATE TABLE `t_gestion_dinero` (
  `numero_informe` int(11) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `cantidad` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_informe_social`
--

CREATE TABLE `t_informe_social` (
  `numero` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `nacionalidad_aprendiz` char(1) NOT NULL,
  `cedula_aprendiz` varchar(12) NOT NULL,
  `codigo_oficio` int(11) NOT NULL,
  `turno` char(1) NOT NULL,
  `nacionalidad_fac` char(1) NOT NULL,
  `cedula_facilitador` varchar(12) NOT NULL,
  `condicion_vivienda` varchar(1000) NOT NULL,
  `caracteristicas_generales` varchar(1000) NOT NULL,
  `diagnostico_social` varchar(1000) NOT NULL,
  `diagnostico_preliminar` varchar(1000) NOT NULL,
  `conclusiones` varchar(1000) NOT NULL,
  `enfermos` char(1) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'E'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_justificativo`
--

CREATE TABLE `t_justificativo` (
  `numero` int(11) NOT NULL,
  `numero_asistencia` int(11) NOT NULL,
  `extencion_img` varchar(6) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_modulo`
--

CREATE TABLE `t_modulo` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `repetir_modulo` char(1) DEFAULT NULL,
  `codigo_oficio` int(11) DEFAULT NULL,
  `orden` int(11) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `t_modulo`
--

INSERT INTO `t_modulo` (`codigo`, `nombre`, `repetir_modulo`, `codigo_oficio`, `orden`, `estatus`) VALUES
(1, 'Unidad programa del cont. inmediato', NULL, 1, 1, 'A'),
(2, 'Unidad programa del cont. relativo', NULL, 1, 2, 'A'),
(3, 'Unidad programa del cont. complementario', NULL, 1, 3, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_modulo_asig`
--

CREATE TABLE `t_modulo_asig` (
  `codigo` int(11) NOT NULL,
  `codigo_modulo` int(11) NOT NULL,
  `codigo_asignatura` int(11) NOT NULL,
  `horas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `t_modulo_asig`
--

INSERT INTO `t_modulo_asig` (`codigo`, `codigo_modulo`, `codigo_asignatura`, `horas`) VALUES
(1, 1, 9, 100),
(2, 1, 10, 130),
(3, 1, 11, 100),
(4, 1, 12, 80),
(5, 1, 13, 260),
(6, 1, 14, 150),
(7, 2, 15, 30),
(8, 2, 16, 60),
(9, 2, 17, 230),
(10, 2, 18, 50),
(11, 2, 19, 80),
(12, 2, 20, 30),
(13, 2, 21, 30),
(14, 2, 22, 30),
(15, 3, 23, 80);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_modulo_sistema`
--

CREATE TABLE `t_modulo_sistema` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `posicion` int(2) NOT NULL,
  `icono` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `t_modulo_sistema`
--

INSERT INTO `t_modulo_sistema` (`codigo`, `nombre`, `posicion`, `icono`) VALUES
(1, 'Aprendiz', 1, 'fas fa-user-graduate'),
(3, 'Reportes', 4, 'fas fa-file-pdf'),
(4, 'Registros', 5, 'fas fa-pen-square'),
(5, 'Seguridad sistema', 6, 'fa fa-shield-alt'),
(6, 'Configuración', 7, 'fa fa-cog'),
(7, 'Gestión', 3, 'fas fa-user-graduate'),
(8, 'Curso', 2, 'fas fa-file');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_municipio`
--

CREATE TABLE `t_municipio` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(70) NOT NULL,
  `codigo_estado` int(11) NOT NULL,
  `estatus` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `t_municipio`
--

INSERT INTO `t_municipio` (`codigo`, `nombre`, `codigo_estado`, `estatus`) VALUES
(1, 'Alto Orinoco', 1, 'A'),
(2, 'Atabapo', 1, 'A'),
(3, 'Atures', 1, 'A'),
(4, 'Autana', 1, 'A'),
(5, 'Manapiare', 1, 'A'),
(6, 'Maroa', 1, 'A'),
(7, 'Río Negro', 1, 'A'),
(8, 'Anaco', 2, 'A'),
(9, 'Aragua', 2, 'A'),
(10, 'Manuel Ezequiel Bruzual', 2, 'A'),
(11, 'Diego Bautista Urbaneja', 2, 'A'),
(12, 'Fernando Peñalver', 2, 'A'),
(13, 'Francisco Del Carmen Carvajal', 2, 'A'),
(14, 'General Sir Arthur McGregor', 2, 'A'),
(15, 'Guanta', 2, 'A'),
(16, 'Independencia', 2, 'A'),
(17, 'José Gregorio Monagas', 2, 'A'),
(18, 'Juan Antonio Sotillo', 2, 'A'),
(19, 'Juan Manuel Cajigal', 2, 'A'),
(20, 'Libertad', 2, 'A'),
(21, 'Francisco de Miranda', 2, 'A'),
(22, 'Pedro María Freites', 2, 'A'),
(23, 'Píritu', 2, 'A'),
(24, 'San José de Guanipa', 2, 'A'),
(25, 'San Juan de Capistrano', 2, 'A'),
(26, 'Santa Ana', 2, 'A'),
(27, 'Simón Bolívar', 2, 'A'),
(28, 'Simón Rodríguez', 2, 'A'),
(29, 'Achaguas', 3, 'A'),
(30, 'Biruaca', 3, 'A'),
(31, 'Muñóz', 3, 'A'),
(32, 'Páez', 3, 'A'),
(33, 'Pedro Camejo', 3, 'A'),
(34, 'Rómulo Gallegos', 3, 'A'),
(35, 'San Fernando', 3, 'A'),
(36, 'Atanasio Girardot', 4, 'A'),
(37, 'Bolívar', 4, 'A'),
(38, 'Camatagua', 4, 'A'),
(39, 'Francisco Linares Alcántara', 4, 'A'),
(40, 'José Ángel Lamas', 4, 'A'),
(41, 'José Félix Ribas', 4, 'A'),
(42, 'José Rafael Revenga', 4, 'A'),
(43, 'Libertador', 4, 'A'),
(44, 'Mario Briceño Iragorry', 4, 'A'),
(45, 'Ocumare de la Costa de Oro', 4, 'A'),
(46, 'San Casimiro', 4, 'A'),
(47, 'San Sebastián', 4, 'A'),
(48, 'Santiago Mariño', 4, 'A'),
(49, 'Santos Michelena', 4, 'A'),
(50, 'Sucre', 4, 'A'),
(51, 'Tovar', 4, 'A'),
(52, 'Urdaneta', 4, 'A'),
(53, 'Zamora', 4, 'A'),
(54, 'Alberto Arvelo Torrealba', 5, 'A'),
(55, 'Andrés Eloy Blanco', 5, 'A'),
(56, 'Antonio José de Sucre', 5, 'A'),
(57, 'Arismendi', 5, 'A'),
(58, 'Barinas', 5, 'A'),
(59, 'Bolívar', 5, 'A'),
(60, 'Cruz Paredes', 5, 'A'),
(61, 'Ezequiel Zamora', 5, 'A'),
(62, 'Obispos', 5, 'A'),
(63, 'Pedraza', 5, 'A'),
(64, 'Rojas', 5, 'A'),
(65, 'Sosa', 5, 'A'),
(66, 'Caroní', 6, 'A'),
(67, 'Cedeño', 6, 'A'),
(68, 'El Callao', 6, 'A'),
(69, 'Gran Sabana', 6, 'A'),
(70, 'Heres', 6, 'A'),
(71, 'Piar', 6, 'A'),
(72, 'Angostura (Raúl Leoni)', 6, 'A'),
(73, 'Roscio', 6, 'A'),
(74, 'Sifontes', 6, 'A'),
(75, 'Sucre', 6, 'A'),
(76, 'Padre Pedro Chien', 6, 'A'),
(77, 'Bejuma', 7, 'A'),
(78, 'Carlos Arvelo', 7, 'A'),
(79, 'Diego Ibarra', 7, 'A'),
(80, 'Guacara', 7, 'A'),
(81, 'Juan José Mora', 7, 'A'),
(82, 'Libertador', 7, 'A'),
(83, 'Los Guayos', 7, 'A'),
(84, 'Miranda', 7, 'A'),
(85, 'Montalbán', 7, 'A'),
(86, 'Naguanagua', 7, 'A'),
(87, 'Puerto Cabello', 7, 'A'),
(88, 'San Diego', 7, 'A'),
(89, 'San Joaquín', 7, 'A'),
(90, 'Valencia', 7, 'A'),
(91, 'Anzoátegui', 8, 'A'),
(92, 'Tinaquillo', 8, 'A'),
(93, 'Girardot', 8, 'A'),
(94, 'Lima Blanco', 8, 'A'),
(95, 'Pao de San Juan Bautista', 8, 'A'),
(96, 'Ricaurte', 8, 'A'),
(97, 'Rómulo Gallegos', 8, 'A'),
(98, 'San Carlos', 8, 'A'),
(99, 'Tinaco', 8, 'A'),
(100, 'Antonio Díaz', 9, 'A'),
(101, 'Casacoima', 9, 'A'),
(102, 'Pedernales', 9, 'A'),
(103, 'Tucupita', 9, 'A'),
(104, 'Acosta', 10, 'A'),
(105, 'Bolívar', 10, 'A'),
(106, 'Buchivacoa', 10, 'A'),
(107, 'Cacique Manaure', 10, 'A'),
(108, 'Carirubana', 10, 'A'),
(109, 'Colina', 10, 'A'),
(110, 'Dabajuro', 10, 'A'),
(111, 'Democracia', 10, 'A'),
(112, 'Falcón', 10, 'A'),
(113, 'Federación', 10, 'A'),
(114, 'Jacura', 10, 'A'),
(115, 'José Laurencio Silva', 10, 'A'),
(116, 'Los Taques', 10, 'A'),
(117, 'Mauroa', 10, 'A'),
(118, 'Miranda', 10, 'A'),
(119, 'Monseñor Iturriza', 10, 'A'),
(120, 'Palmasola', 10, 'A'),
(121, 'Petit', 10, 'A'),
(122, 'Píritu', 10, 'A'),
(123, 'San Francisco', 10, 'A'),
(124, 'Sucre', 10, 'A'),
(125, 'Tocópero', 10, 'A'),
(126, 'Unión', 10, 'A'),
(127, 'Urumaco', 10, 'A'),
(128, 'Zamora', 10, 'A'),
(129, 'Camaguán', 11, 'A'),
(130, 'Chaguaramas', 11, 'A'),
(131, 'El Socorro', 11, 'A'),
(132, 'José Félix Ribas', 11, 'A'),
(133, 'José Tadeo Monagas', 11, 'A'),
(134, 'Juan Germán Roscio', 11, 'A'),
(135, 'Julián Mellado', 11, 'A'),
(136, 'Las Mercedes', 11, 'A'),
(137, 'Leonardo Infante', 11, 'A'),
(138, 'Pedro Zaraza', 11, 'A'),
(139, 'Ortíz', 11, 'A'),
(140, 'San Gerónimo de Guayabal', 11, 'A'),
(141, 'San José de Guaribe', 11, 'A'),
(142, 'Santa María de Ipire', 11, 'A'),
(143, 'Sebastián Francisco de Miranda', 11, 'A'),
(144, 'Andrés Eloy Blanco', 12, 'A'),
(145, 'Crespo', 12, 'A'),
(146, 'Iribarren', 12, 'A'),
(147, 'Jiménez', 12, 'A'),
(148, 'Morán', 12, 'A'),
(149, 'Palavecino', 12, 'A'),
(150, 'Simón Planas', 12, 'A'),
(151, 'Torres', 12, 'A'),
(152, 'Urdaneta', 12, 'A'),
(179, 'Alberto Adriani', 13, 'A'),
(180, 'Andrés Bello', 13, 'A'),
(181, 'Antonio Pinto Salinas', 13, 'A'),
(182, 'Aricagua', 13, 'A'),
(183, 'Arzobispo Chacón', 13, 'A'),
(184, 'Campo Elías', 13, 'A'),
(185, 'Caracciolo Parra Olmedo', 13, 'A'),
(186, 'Cardenal Quintero', 13, 'A'),
(187, 'Guaraque', 13, 'A'),
(188, 'Julio César Salas', 13, 'A'),
(189, 'Justo Briceño', 13, 'A'),
(190, 'Libertador', 13, 'A'),
(191, 'Miranda', 13, 'A'),
(192, 'Obispo Ramos de Lora', 13, 'A'),
(193, 'Padre Noguera', 13, 'A'),
(194, 'Pueblo Llano', 13, 'A'),
(195, 'Rangel', 13, 'A'),
(196, 'Rivas Dávila', 13, 'A'),
(197, 'Santos Marquina', 13, 'A'),
(198, 'Sucre', 13, 'A'),
(199, 'Tovar', 13, 'A'),
(200, 'Tulio Febres Cordero', 13, 'A'),
(201, 'Zea', 13, 'A'),
(223, 'Acevedo', 14, 'A'),
(224, 'Andrés Bello', 14, 'A'),
(225, 'Baruta', 14, 'A'),
(226, 'Brión', 14, 'A'),
(227, 'Buroz', 14, 'A'),
(228, 'Carrizal', 14, 'A'),
(229, 'Chacao', 14, 'A'),
(230, 'Cristóbal Rojas', 14, 'A'),
(231, 'El Hatillo', 14, 'A'),
(232, 'Guaicaipuro', 14, 'A'),
(233, 'Independencia', 14, 'A'),
(234, 'Lander', 14, 'A'),
(235, 'Los Salias', 14, 'A'),
(236, 'Páez', 14, 'A'),
(237, 'Paz Castillo', 14, 'A'),
(238, 'Pedro Gual', 14, 'A'),
(239, 'Plaza', 14, 'A'),
(240, 'Simón Bolívar', 14, 'A'),
(241, 'Sucre', 14, 'A'),
(242, 'Urdaneta', 14, 'A'),
(243, 'Zamora', 14, 'A'),
(258, 'Acosta', 15, 'A'),
(259, 'Aguasay', 15, 'A'),
(260, 'Bolívar', 15, 'A'),
(261, 'Caripe', 15, 'A'),
(262, 'Cedeño', 15, 'A'),
(263, 'Ezequiel Zamora', 15, 'A'),
(264, 'Libertador', 15, 'A'),
(265, 'Maturín', 15, 'A'),
(266, 'Piar', 15, 'A'),
(267, 'Punceres', 15, 'A'),
(268, 'Santa Bárbara', 15, 'A'),
(269, 'Sotillo', 15, 'A'),
(270, 'Uracoa', 15, 'A'),
(271, 'Antolín del Campo', 16, 'A'),
(272, 'Arismendi', 16, 'A'),
(273, 'García', 16, 'A'),
(274, 'Gómez', 16, 'A'),
(275, 'Maneiro', 16, 'A'),
(276, 'Marcano', 16, 'A'),
(277, 'Mariño', 16, 'A'),
(278, 'Península de Macanao', 16, 'A'),
(279, 'Tubores', 16, 'A'),
(280, 'Villalba', 16, 'A'),
(281, 'Díaz', 16, 'A'),
(282, 'Agua Blanca', 17, 'A'),
(283, 'Araure', 17, 'A'),
(284, 'Esteller', 17, 'A'),
(285, 'Guanare', 17, 'A'),
(286, 'Guanarito', 17, 'A'),
(287, 'Monseñor José Vicente de Unda', 17, 'A'),
(288, 'Ospino', 17, 'A'),
(289, 'Páez', 17, 'A'),
(290, 'Papelón', 17, 'A'),
(291, 'San Genaro de Boconoíto', 17, 'A'),
(292, 'San Rafael de Onoto', 17, 'A'),
(293, 'Santa Rosalía', 17, 'A'),
(294, 'Sucre', 17, 'A'),
(295, 'Turén', 17, 'A'),
(296, 'Andrés Eloy Blanco', 18, 'A'),
(297, 'Andrés Mata', 18, 'A'),
(298, 'Arismendi', 18, 'A'),
(299, 'Benítez', 18, 'A'),
(300, 'Bermúdez', 18, 'A'),
(301, 'Bolívar', 18, 'A'),
(302, 'Cajigal', 18, 'A'),
(303, 'Cruz Salmerón Acosta', 18, 'A'),
(304, 'Libertador', 18, 'A'),
(305, 'Mariño', 18, 'A'),
(306, 'Mejía', 18, 'A'),
(307, 'Montes', 18, 'A'),
(308, 'Ribero', 18, 'A'),
(309, 'Sucre', 18, 'A'),
(310, 'Valdéz', 18, 'A'),
(341, 'Andrés Bello', 19, 'A'),
(342, 'Antonio Rómulo Costa', 19, 'A'),
(343, 'Ayacucho', 19, 'A'),
(344, 'Bolívar', 19, 'A'),
(345, 'Cárdenas', 19, 'A'),
(346, 'Córdoba', 19, 'A'),
(347, 'Fernández Feo', 19, 'A'),
(348, 'Francisco de Miranda', 19, 'A'),
(349, 'García de Hevia', 19, 'A'),
(350, 'Guásimos', 19, 'A'),
(351, 'Independencia', 19, 'A'),
(352, 'Jáuregui', 19, 'A'),
(353, 'José María Vargas', 19, 'A'),
(354, 'Junín', 19, 'A'),
(355, 'Libertad', 19, 'A'),
(356, 'Libertador', 19, 'A'),
(357, 'Lobatera', 19, 'A'),
(358, 'Michelena', 19, 'A'),
(359, 'Panamericano', 19, 'A'),
(360, 'Pedro María Ureña', 19, 'A'),
(361, 'Rafael Urdaneta', 19, 'A'),
(362, 'Samuel Darío Maldonado', 19, 'A'),
(363, 'San Cristóbal', 19, 'A'),
(364, 'Seboruco', 19, 'A'),
(365, 'Simón Rodríguez', 19, 'A'),
(366, 'Sucre', 19, 'A'),
(367, 'Torbes', 19, 'A'),
(368, 'Uribante', 19, 'A'),
(369, 'San Judas Tadeo', 19, 'A'),
(370, 'Andrés Bello', 20, 'A'),
(371, 'Boconó', 20, 'A'),
(372, 'Bolívar', 20, 'A'),
(373, 'Candelaria', 20, 'A'),
(374, 'Carache', 20, 'A'),
(375, 'Escuque', 20, 'A'),
(376, 'José Felipe Márquez Cañizalez', 20, 'A'),
(377, 'Juan Vicente Campos Elías', 20, 'A'),
(378, 'La Ceiba', 20, 'A'),
(379, 'Miranda', 20, 'A'),
(380, 'Monte Carmelo', 20, 'A'),
(381, 'Motatán', 20, 'A'),
(382, 'Pampán', 20, 'A'),
(383, 'Pampanito', 20, 'A'),
(384, 'Rafael Rangel', 20, 'A'),
(385, 'San Rafael de Carvajal', 20, 'A'),
(386, 'Sucre', 20, 'A'),
(387, 'Trujillo', 20, 'A'),
(388, 'Urdaneta', 20, 'A'),
(389, 'Valera', 20, 'A'),
(390, 'Vargas', 21, 'A'),
(391, 'Arístides Bastidas', 22, 'A'),
(392, 'Bolívar', 22, 'A'),
(407, 'Bruzual', 22, 'A'),
(408, 'Cocorote', 22, 'A'),
(409, 'Independencia', 22, 'A'),
(410, 'José Antonio Páez', 22, 'A'),
(411, 'La Trinidad', 22, 'A'),
(412, 'Manuel Monge', 22, 'A'),
(413, 'Nirgua', 22, 'A'),
(414, 'Peña', 22, 'A'),
(415, 'San Felipe', 22, 'A'),
(416, 'Sucre', 22, 'A'),
(417, 'Urachiche', 22, 'A'),
(418, 'José Joaquín Veroes', 22, 'A'),
(441, 'Almirante Padilla', 23, 'A'),
(442, 'Baralt', 23, 'A'),
(443, 'Cabimas', 23, 'A'),
(444, 'Catatumbo', 23, 'A'),
(445, 'Colón', 23, 'A'),
(446, 'Francisco Javier Pulgar', 23, 'A'),
(447, 'Páez', 23, 'A'),
(448, 'Jesús Enrique Losada', 23, 'A'),
(449, 'Jesús María Semprún', 23, 'A'),
(450, 'La Cañada de Urdaneta', 23, 'A'),
(451, 'Lagunillas', 23, 'A'),
(452, 'Machiques de Perijá', 23, 'A'),
(453, 'Mara', 23, 'A'),
(454, 'Maracaibo', 23, 'A'),
(455, 'Miranda', 23, 'A'),
(456, 'Rosario de Perijá', 23, 'A'),
(457, 'San Francisco', 23, 'A'),
(458, 'Santa Rita', 23, 'A'),
(459, 'Simón Bolívar', 23, 'A'),
(460, 'Sucre', 23, 'A'),
(461, 'Valmore Rodríguez', 23, 'A'),
(462, 'Libertador', 24, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_nota`
--

CREATE TABLE `t_nota` (
  `codigo` int(11) NOT NULL,
  `codigo_asignatura` int(11) NOT NULL,
  `numero_ficha` int(11) NOT NULL,
  `nota1` char(2) DEFAULT NULL,
  `nota2` char(2) DEFAULT NULL,
  `nota3` char(2) DEFAULT NULL,
  `nota4` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_ocupacion`
--

CREATE TABLE `t_ocupacion` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `formulario` char(1) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `t_ocupacion`
--

INSERT INTO `t_ocupacion` (`codigo`, `nombre`, `formulario`, `estatus`) VALUES
(1, 'Ingeniero en informática', 'F', 'A'),
(2, 'Estudiante de bachillerato', 'B', 'A'),
(3, 'Asistente administrativo', 'A', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_oficio`
--

CREATE TABLE `t_oficio` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `t_oficio`
--

INSERT INTO `t_oficio` (`codigo`, `nombre`, `estatus`) VALUES
(1, 'Asistente administrativo', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_oficio_modulo`
--

CREATE TABLE `t_oficio_modulo` (
  `codigo_oficio` int(11) NOT NULL,
  `codigo_modulo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_parroquia`
--

CREATE TABLE `t_parroquia` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(70) NOT NULL,
  `codigo_municipio` int(11) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `t_parroquia`
--

INSERT INTO `t_parroquia` (`codigo`, `nombre`, `codigo_municipio`, `estatus`) VALUES
(1, 'Alto Orinoco', 1, 'A'),
(2, 'Huachamacare Acanaña', 1, 'A'),
(3, 'Marawaka Toky Shamanaña', 1, 'A'),
(4, 'Mavaka Mavaka', 1, 'A'),
(5, 'Sierra Parima Parimabé', 1, 'A'),
(6, 'Ucata Laja Lisa', 2, 'A'),
(7, 'Yapacana Macuruco', 2, 'A'),
(8, 'Caname Guarinuma', 2, 'A'),
(9, 'Fernando Girón Tovar', 3, 'A'),
(10, 'Luis Alberto Gómez', 3, 'A'),
(11, 'Pahueña Limón de Parhueña', 3, 'A'),
(12, 'Platanillal Platanillal', 3, 'A'),
(13, 'Samariapo', 4, 'A'),
(14, 'Sipapo', 4, 'A'),
(15, 'Munduapo', 4, 'A'),
(16, 'Guayapo', 4, 'A'),
(17, 'Alto Ventuari', 5, 'A'),
(18, 'Medio Ventuari', 5, 'A'),
(19, 'Bajo Ventuari', 5, 'A'),
(20, 'Victorino', 6, 'A'),
(21, 'Comunidad', 6, 'A'),
(22, 'Casiquiare', 7, 'A'),
(23, 'Cocuy', 7, 'A'),
(24, 'San Carlos de Río Negro', 7, 'A'),
(25, 'Solano', 7, 'A'),
(26, 'Anaco', 8, 'A'),
(27, 'San Joaquín', 8, 'A'),
(28, 'Cachipo', 9, 'A'),
(29, 'Aragua de Barcelona', 9, 'A'),
(30, 'Lechería', 11, 'A'),
(31, 'El Morro', 11, 'A'),
(32, 'Puerto Píritu', 12, 'A'),
(33, 'San Miguel', 12, 'A'),
(34, 'Sucre', 12, 'A'),
(35, 'Valle de Guanape', 13, 'A'),
(36, 'Santa Bárbara', 13, 'A'),
(37, 'El Chaparro', 14, 'A'),
(38, 'Tomás Alfaro', 14, 'A'),
(39, 'Calatrava', 14, 'A'),
(40, 'Guanta', 15, 'A'),
(41, 'Chorrerón', 15, 'A'),
(42, 'Mamo', 16, 'A'),
(43, 'Soledad', 16, 'A'),
(44, 'Mapire', 17, 'A'),
(45, 'Piar', 17, 'A'),
(46, 'Santa Clara', 17, 'A'),
(47, 'San Diego de Cabrutica', 17, 'A'),
(48, 'Uverito', 17, 'A'),
(49, 'Zuata', 17, 'A'),
(50, 'Puerto La Cruz', 18, 'A'),
(51, 'Pozuelos', 18, 'A'),
(52, 'Onoto', 19, 'A'),
(53, 'San Pablo', 19, 'A'),
(54, 'San Mateo', 20, 'A'),
(55, 'El Carito', 20, 'A'),
(56, 'Santa Inés', 20, 'A'),
(57, 'La Romereña', 20, 'A'),
(58, 'Atapirire', 21, 'A'),
(59, 'Boca del Pao', 21, 'A'),
(60, 'El Pao', 21, 'A'),
(61, 'Pariaguán', 21, 'A'),
(62, 'Cantaura', 22, 'A'),
(63, 'Libertador', 22, 'A'),
(64, 'Santa Rosa', 22, 'A'),
(65, 'Urica', 22, 'A'),
(66, 'Píritu', 23, 'A'),
(67, 'San Francisco', 23, 'A'),
(68, 'San José de Guanipa', 24, 'A'),
(69, 'Boca de Uchire', 25, 'A'),
(70, 'Boca de Chávez', 25, 'A'),
(71, 'Pueblo Nuevo', 26, 'A'),
(72, 'Santa Ana', 26, 'A'),
(73, 'Bergantín', 27, 'A'),
(74, 'Caigua', 27, 'A'),
(75, 'El Carmen', 27, 'A'),
(76, 'El Pilar', 27, 'A'),
(77, 'Naricual', 27, 'A'),
(78, 'San Crsitóbal', 27, 'A'),
(79, 'Edmundo Barrios', 28, 'A'),
(80, 'Miguel Otero Silva', 28, 'A'),
(81, 'Achaguas', 29, 'A'),
(82, 'Apurito', 29, 'A'),
(83, 'El Yagual', 29, 'A'),
(84, 'Guachara', 29, 'A'),
(85, 'Mucuritas', 29, 'A'),
(86, 'Queseras del medio', 29, 'A'),
(87, 'Biruaca', 30, 'A'),
(88, 'Bruzual', 31, 'A'),
(89, 'Mantecal', 31, 'A'),
(90, 'Quintero', 31, 'A'),
(91, 'Rincón Hondo', 31, 'A'),
(92, 'San Vicente', 31, 'A'),
(93, 'Guasdualito', 32, 'A'),
(94, 'Aramendi', 32, 'A'),
(95, 'El Amparo', 32, 'A'),
(96, 'San Camilo', 32, 'A'),
(97, 'Urdaneta', 32, 'A'),
(98, 'San Juan de Payara', 33, 'A'),
(99, 'Codazzi', 33, 'A'),
(100, 'Cunaviche', 33, 'A'),
(101, 'Elorza', 34, 'A'),
(102, 'La Trinidad', 34, 'A'),
(103, 'San Fernando', 35, 'A'),
(104, 'El Recreo', 35, 'A'),
(105, 'Peñalver', 35, 'A'),
(106, 'San Rafael de Atamaica', 35, 'A'),
(107, 'Pedro José Ovalles', 36, 'A'),
(108, 'Joaquín Crespo', 36, 'A'),
(109, 'José Casanova Godoy', 36, 'A'),
(110, 'Madre María de San José', 36, 'A'),
(111, 'Andrés Eloy Blanco', 36, 'A'),
(112, 'Los Tacarigua', 36, 'A'),
(113, 'Las Delicias', 36, 'A'),
(114, 'Choroní', 36, 'A'),
(115, 'Bolívar', 37, 'A'),
(116, 'Camatagua', 38, 'A'),
(117, 'Carmen de Cura', 38, 'A'),
(118, 'Santa Rita', 39, 'A'),
(119, 'Francisco de Miranda', 39, 'A'),
(120, 'Moseñor Feliciano González', 39, 'A'),
(121, 'Santa Cruz', 40, 'A'),
(122, 'José Félix Ribas', 41, 'A'),
(123, 'Castor Nieves Ríos', 41, 'A'),
(124, 'Las Guacamayas', 41, 'A'),
(125, 'Pao de Zárate', 41, 'A'),
(126, 'Zuata', 41, 'A'),
(127, 'José Rafael Revenga', 42, 'A'),
(128, 'Palo Negro', 43, 'A'),
(129, 'San Martín de Porres', 43, 'A'),
(130, 'El Limón', 44, 'A'),
(131, 'Caña de Azúcar', 44, 'A'),
(132, 'Ocumare de la Costa', 45, 'A'),
(133, 'San Casimiro', 46, 'A'),
(134, 'Güiripa', 46, 'A'),
(135, 'Ollas de Caramacate', 46, 'A'),
(136, 'Valle Morín', 46, 'A'),
(137, 'San Sebastían', 47, 'A'),
(138, 'Turmero', 48, 'A'),
(139, 'Arevalo Aponte', 48, 'A'),
(140, 'Chuao', 48, 'A'),
(141, 'Samán de Güere', 48, 'A'),
(142, 'Alfredo Pacheco Miranda', 48, 'A'),
(143, 'Santos Michelena', 49, 'A'),
(144, 'Tiara', 49, 'A'),
(145, 'Cagua', 50, 'A'),
(146, 'Bella Vista', 50, 'A'),
(147, 'Tovar', 51, 'A'),
(148, 'Urdaneta', 52, 'A'),
(149, 'Las Peñitas', 52, 'A'),
(150, 'San Francisco de Cara', 52, 'A'),
(151, 'Taguay', 52, 'A'),
(152, 'Zamora', 53, 'A'),
(153, 'Magdaleno', 53, 'A'),
(154, 'San Francisco de Asís', 53, 'A'),
(155, 'Valles de Tucutunemo', 53, 'A'),
(156, 'Augusto Mijares', 53, 'A'),
(157, 'Sabaneta', 54, 'A'),
(158, 'Juan Antonio Rodríguez Domínguez', 54, 'A'),
(159, 'El Cantón', 55, 'A'),
(160, 'Santa Cruz de Guacas', 55, 'A'),
(161, 'Puerto Vivas', 55, 'A'),
(162, 'Ticoporo', 56, 'A'),
(163, 'Nicolás Pulido', 56, 'A'),
(164, 'Andrés Bello', 56, 'A'),
(165, 'Arismendi', 57, 'A'),
(166, 'Guadarrama', 57, 'A'),
(167, 'La Unión', 57, 'A'),
(168, 'San Antonio', 57, 'A'),
(169, 'Barinas', 58, 'A'),
(170, 'Alberto Arvelo Larriva', 58, 'A'),
(171, 'San Silvestre', 58, 'A'),
(172, 'Santa Inés', 58, 'A'),
(173, 'Santa Lucía', 58, 'A'),
(174, 'Torumos', 58, 'A'),
(175, 'El Carmen', 58, 'A'),
(176, 'Rómulo Betancourt', 58, 'A'),
(177, 'Corazón de Jesús', 58, 'A'),
(178, 'Ramón Ignacio Méndez', 58, 'A'),
(179, 'Alto Barinas', 58, 'A'),
(180, 'Manuel Palacio Fajardo', 58, 'A'),
(181, 'Juan Antonio Rodríguez Domínguez', 58, 'A'),
(182, 'Dominga Ortiz de Páez', 58, 'A'),
(183, 'Barinitas', 59, 'A'),
(184, 'Altamira de Cáceres', 59, 'A'),
(185, 'Calderas', 59, 'A'),
(186, 'Barrancas', 60, 'A'),
(187, 'El Socorro', 60, 'A'),
(188, 'Mazparrito', 60, 'A'),
(189, 'Santa Bárbara', 61, 'A'),
(190, 'Pedro Briceño Méndez', 61, 'A'),
(191, 'Ramón Ignacio Méndez', 61, 'A'),
(192, 'José Ignacio del Pumar', 61, 'A'),
(193, 'Obispos', 62, 'A'),
(194, 'Guasimitos', 62, 'A'),
(195, 'El Real', 62, 'A'),
(196, 'La Luz', 62, 'A'),
(197, 'Ciudad Bolívia', 63, 'A'),
(198, 'José Ignacio Briceño', 63, 'A'),
(199, 'José Félix Ribas', 63, 'A'),
(200, 'Páez', 63, 'A'),
(201, 'Libertad', 64, 'A'),
(202, 'Dolores', 64, 'A'),
(203, 'Santa Rosa', 64, 'A'),
(204, 'Palacio Fajardo', 64, 'A'),
(205, 'Ciudad de Nutrias', 65, 'A'),
(206, 'El Regalo', 65, 'A'),
(207, 'Puerto Nutrias', 65, 'A'),
(208, 'Santa Catalina', 65, 'A'),
(209, 'Cachamay', 66, 'A'),
(210, 'Chirica', 66, 'A'),
(211, 'Dalla Costa', 66, 'A'),
(212, 'Once de Abril', 66, 'A'),
(213, 'Simón Bolívar', 66, 'A'),
(214, 'Unare', 66, 'A'),
(215, 'Universidad', 66, 'A'),
(216, 'Vista al Sol', 66, 'A'),
(217, 'Pozo Verde', 66, 'A'),
(218, 'Yocoima', 66, 'A'),
(219, '5 de Julio', 66, 'A'),
(220, 'Cedeño', 67, 'A'),
(221, 'Altagracia', 67, 'A'),
(222, 'Ascensión Farreras', 67, 'A'),
(223, 'Guaniamo', 67, 'A'),
(224, 'La Urbana', 67, 'A'),
(225, 'Pijiguaos', 67, 'A'),
(226, 'El Callao', 68, 'A'),
(227, 'Gran Sabana', 69, 'A'),
(228, 'Ikabarú', 69, 'A'),
(229, 'Catedral', 70, 'A'),
(230, 'Zea', 70, 'A'),
(231, 'Orinoco', 70, 'A'),
(232, 'José Antonio Páez', 70, 'A'),
(233, 'Marhuanta', 70, 'A'),
(234, 'Agua Salada', 70, 'A'),
(235, 'Vista Hermosa', 70, 'A'),
(236, 'La Sabanita', 70, 'A'),
(237, 'Panapana', 70, 'A'),
(238, 'Andrés Eloy Blanco', 71, 'A'),
(239, 'Pedro Cova', 71, 'A'),
(240, 'Raúl Leoni', 72, 'A'),
(241, 'Barceloneta', 72, 'A'),
(242, 'Santa Bárbara', 72, 'A'),
(243, 'San Francisco', 72, 'A'),
(244, 'Roscio', 73, 'A'),
(245, 'Salóm', 73, 'A'),
(246, 'Sifontes', 74, 'A'),
(247, 'Dalla Costa', 74, 'A'),
(248, 'San Isidro', 74, 'A'),
(249, 'Sucre', 75, 'A'),
(250, 'Aripao', 75, 'A'),
(251, 'Guarataro', 75, 'A'),
(252, 'Las Majadas', 75, 'A'),
(253, 'Moitaco', 75, 'A'),
(254, 'Padre Pedro Chien', 76, 'A'),
(255, 'Río Grande', 76, 'A'),
(256, 'Bejuma', 77, 'A'),
(257, 'Canoabo', 77, 'A'),
(258, 'Simón Bolívar', 77, 'A'),
(259, 'Güigüe', 78, 'A'),
(260, 'Carabobo', 78, 'A'),
(261, 'Tacarigua', 78, 'A'),
(262, 'Mariara', 79, 'A'),
(263, 'Aguas Calientes', 79, 'A'),
(264, 'Ciudad Alianza', 80, 'A'),
(265, 'Guacara', 80, 'A'),
(266, 'Yagua', 80, 'A'),
(267, 'Morón', 81, 'A'),
(268, 'Yagua', 81, 'A'),
(269, 'Tocuyito', 82, 'A'),
(270, 'Independencia', 82, 'A'),
(271, 'Los Guayos', 83, 'A'),
(272, 'Miranda', 84, 'A'),
(273, 'Montalbán', 85, 'A'),
(274, 'Naguanagua', 86, 'A'),
(275, 'Bartolomé Salóm', 87, 'A'),
(276, 'Democracia', 87, 'A'),
(277, 'Fraternidad', 87, 'A'),
(278, 'Goaigoaza', 87, 'A'),
(279, 'Juan José Flores', 87, 'A'),
(280, 'Unión', 87, 'A'),
(281, 'Borburata', 87, 'A'),
(282, 'Patanemo', 87, 'A'),
(283, 'San Diego', 88, 'A'),
(284, 'San Joaquín', 89, 'A'),
(285, 'Candelaria', 90, 'A'),
(286, 'Catedral', 90, 'A'),
(287, 'El Socorro', 90, 'A'),
(288, 'Miguel Peña', 90, 'A'),
(289, 'Rafael Urdaneta', 90, 'A'),
(290, 'San Blas', 90, 'A'),
(291, 'San José', 90, 'A'),
(292, 'Santa Rosa', 90, 'A'),
(293, 'Negro Primero', 90, 'A'),
(294, 'Cojedes', 91, 'A'),
(295, 'Juan de Mata Suárez', 91, 'A'),
(296, 'Tinaquillo', 92, 'A'),
(297, 'El Baúl', 93, 'A'),
(298, 'Sucre', 93, 'A'),
(299, 'La Aguadita', 94, 'A'),
(300, 'Macapo', 94, 'A'),
(301, 'El Pao', 95, 'A'),
(302, 'El Amparo', 96, 'A'),
(303, 'Libertad de Cojedes', 96, 'A'),
(304, 'Rómulo Gallegos', 97, 'A'),
(305, 'San Carlos de Austria', 98, 'A'),
(306, 'Juan Ángel Bravo', 98, 'A'),
(307, 'Manuel Manrique', 98, 'A'),
(308, 'General en Jefe José Laurencio Silva', 99, 'A'),
(309, 'Curiapo', 100, 'A'),
(310, 'Almirante Luis Brión', 100, 'A'),
(311, 'Francisco Aniceto Lugo', 100, 'A'),
(312, 'Manuel Renaud', 100, 'A'),
(313, 'Padre Barral', 100, 'A'),
(314, 'Santos de Abelgas', 100, 'A'),
(315, 'Imataca', 101, 'A'),
(316, 'Cinco de Julio', 101, 'A'),
(317, 'Juan Bautista Arismendi', 101, 'A'),
(318, 'Manuel Piar', 101, 'A'),
(319, 'Rómulo Gallegos', 101, 'A'),
(320, 'Pedernales', 102, 'A'),
(321, 'Luis Beltrán Prieto Figueroa', 102, 'A'),
(322, 'San José (Delta Amacuro)', 103, 'A'),
(323, 'José Vidal Marcano', 103, 'A'),
(324, 'Juan Millán', 103, 'A'),
(325, 'Leonardo Ruíz Pineda', 103, 'A'),
(326, 'Mariscal Antonio José de Sucre', 103, 'A'),
(327, 'Monseñor Argimiro García', 103, 'A'),
(328, 'San Rafael (Delta Amacuro)', 103, 'A'),
(329, 'Virgen del Valle', 103, 'A'),
(330, 'Clarines', 10, 'A'),
(331, 'Guanape', 10, 'A'),
(332, 'Sabana de Uchire', 10, 'A'),
(333, 'Capadare', 104, 'A'),
(334, 'La Pastora', 104, 'A'),
(335, 'Libertador', 104, 'A'),
(336, 'San Juan de los Cayos', 104, 'A'),
(337, 'Aracua', 105, 'A'),
(338, 'La Peña', 105, 'A'),
(339, 'San Luis', 105, 'A'),
(340, 'Bariro', 106, 'A'),
(341, 'Borojó', 106, 'A'),
(342, 'Capatárida', 106, 'A'),
(343, 'Guajiro', 106, 'A'),
(344, 'Seque', 106, 'A'),
(345, 'Zazárida', 106, 'A'),
(346, 'Valle de Eroa', 106, 'A'),
(347, 'Cacique Manaure', 107, 'A'),
(348, 'Norte', 108, 'A'),
(349, 'Carirubana', 108, 'A'),
(350, 'Santa Ana', 108, 'A'),
(351, 'Urbana Punta Cardón', 108, 'A'),
(352, 'La Vela de Coro', 109, 'A'),
(353, 'Acurigua', 109, 'A'),
(354, 'Guaibacoa', 109, 'A'),
(355, 'Las Calderas', 109, 'A'),
(356, 'Macoruca', 109, 'A'),
(357, 'Dabajuro', 110, 'A'),
(358, 'Agua Clara', 111, 'A'),
(359, 'Avaria', 111, 'A'),
(360, 'Pedregal', 111, 'A'),
(361, 'Piedra Grande', 111, 'A'),
(362, 'Purureche', 111, 'A'),
(363, 'Adaure', 112, 'A'),
(364, 'Adícora', 112, 'A'),
(365, 'Baraived', 112, 'A'),
(366, 'Buena Vista', 112, 'A'),
(367, 'Jadacaquiva', 112, 'A'),
(368, 'El Vínculo', 112, 'A'),
(369, 'El Hato', 112, 'A'),
(370, 'Moruy', 112, 'A'),
(371, 'Pueblo Nuevo', 112, 'A'),
(372, 'Agua Larga', 113, 'A'),
(373, 'El Paují', 113, 'A'),
(374, 'Independencia', 113, 'A'),
(375, 'Mapararí', 113, 'A'),
(376, 'Agua Linda', 114, 'A'),
(377, 'Araurima', 114, 'A'),
(378, 'Jacura', 114, 'A'),
(379, 'Tucacas', 115, 'A'),
(380, 'Boca de Aroa', 115, 'A'),
(381, 'Los Taques', 116, 'A'),
(382, 'Judibana', 116, 'A'),
(383, 'Mene de Mauroa', 117, 'A'),
(384, 'San Félix', 117, 'A'),
(385, 'Casigua', 117, 'A'),
(386, 'Guzmán Guillermo', 118, 'A'),
(387, 'Mitare', 118, 'A'),
(388, 'Río Seco', 118, 'A'),
(389, 'Sabaneta', 118, 'A'),
(390, 'San Antonio', 118, 'A'),
(391, 'San Gabriel', 118, 'A'),
(392, 'Santa Ana', 118, 'A'),
(393, 'Boca del Tocuyo', 119, 'A'),
(394, 'Chichiriviche', 119, 'A'),
(395, 'Tocuyo de la Costa', 119, 'A'),
(396, 'Palmasola', 120, 'A'),
(397, 'Cabure', 121, 'A'),
(398, 'Colina', 121, 'A'),
(399, 'Curimagua', 121, 'A'),
(400, 'San José de la Costa', 122, 'A'),
(401, 'Píritu', 122, 'A'),
(402, 'San Francisco', 123, 'A'),
(403, 'Sucre', 124, 'A'),
(404, 'Pecaya', 124, 'A'),
(405, 'Tocópero', 125, 'A'),
(406, 'El Charal', 126, 'A'),
(407, 'Las Vegas del Tuy', 126, 'A'),
(408, 'Santa Cruz de Bucaral', 126, 'A'),
(409, 'Bruzual', 127, 'A'),
(410, 'Urumaco', 127, 'A'),
(411, 'Puerto Cumarebo', 128, 'A'),
(412, 'La Ciénaga', 128, 'A'),
(413, 'La Soledad', 128, 'A'),
(414, 'Pueblo Cumarebo', 128, 'A'),
(415, 'Zazárida', 128, 'A'),
(416, 'Churuguara', 113, 'A'),
(417, 'Camaguán', 129, 'A'),
(418, 'Puerto Miranda', 129, 'A'),
(419, 'Uverito', 129, 'A'),
(420, 'Chaguaramas', 130, 'A'),
(421, 'El Socorro', 131, 'A'),
(422, 'Tucupido', 132, 'A'),
(423, 'San Rafael de Laya', 132, 'A'),
(424, 'Altagracia de Orituco', 133, 'A'),
(425, 'San Rafael de Orituco', 133, 'A'),
(426, 'San Francisco Javier de Lezama', 133, 'A'),
(427, 'Paso Real de Macaira', 133, 'A'),
(428, 'Carlos Soublette', 133, 'A'),
(429, 'San Francisco de Macaira', 133, 'A'),
(430, 'Libertad de Orituco', 133, 'A'),
(431, 'Cantaclaro', 134, 'A'),
(432, 'San Juan de los Morros', 134, 'A'),
(433, 'Parapara', 134, 'A'),
(434, 'El Sombrero', 135, 'A'),
(435, 'Sosa', 135, 'A'),
(436, 'Las Mercedes', 136, 'A'),
(437, 'Cabruta', 136, 'A'),
(438, 'Santa Rita de Manapire', 136, 'A'),
(439, 'Valle de la Pascua', 137, 'A'),
(440, 'Espino', 137, 'A'),
(441, 'San José de Unare', 138, 'A'),
(442, 'Zaraza', 138, 'A'),
(443, 'San José de Tiznados', 139, 'A'),
(444, 'San Francisco de Tiznados', 139, 'A'),
(445, 'San Lorenzo de Tiznados', 139, 'A'),
(446, 'Ortiz', 139, 'A'),
(447, 'Guayabal', 140, 'A'),
(448, 'Cazorla', 140, 'A'),
(449, 'San José de Guaribe', 141, 'A'),
(450, 'Uveral', 141, 'A'),
(451, 'Santa María de Ipire', 142, 'A'),
(452, 'Altamira', 142, 'A'),
(453, 'El Calvario', 143, 'A'),
(454, 'El Rastro', 143, 'A'),
(455, 'Guardatinajas', 143, 'A'),
(456, 'Capital Urbana Calabozo', 143, 'A'),
(457, 'Quebrada Honda de Guache', 144, 'A'),
(458, 'Pío Tamayo', 144, 'A'),
(459, 'Yacambú', 144, 'A'),
(460, 'Fréitez', 145, 'A'),
(461, 'José María Blanco', 145, 'A'),
(462, 'Catedral', 146, 'A'),
(463, 'Concepción', 146, 'A'),
(464, 'El Cují', 146, 'A'),
(465, 'Juan de Villegas', 146, 'A'),
(466, 'Santa Rosa', 146, 'A'),
(467, 'Tamaca', 146, 'A'),
(468, 'Unión', 146, 'A'),
(469, 'Aguedo Felipe Alvarado', 146, 'A'),
(470, 'Buena Vista', 146, 'A'),
(471, 'Juárez', 146, 'A'),
(472, 'Juan Bautista Rodríguez', 147, 'A'),
(473, 'Cuara', 147, 'A'),
(474, 'Diego de Lozada', 147, 'A'),
(475, 'Paraíso de San José', 147, 'A'),
(476, 'San Miguel', 147, 'A'),
(477, 'Tintorero', 147, 'A'),
(478, 'José Bernardo Dorante', 147, 'A'),
(479, 'Coronel Mariano Peraza ', 147, 'A'),
(480, 'Bolívar', 148, 'A'),
(481, 'Anzoátegui', 148, 'A'),
(482, 'Guarico', 148, 'A'),
(483, 'Hilario Luna y Luna', 148, 'A'),
(484, 'Humocaro Alto', 148, 'A'),
(485, 'Humocaro Bajo', 148, 'A'),
(486, 'La Candelaria', 148, 'A'),
(487, 'Morán', 148, 'A'),
(488, 'Cabudare', 149, 'A'),
(489, 'José Gregorio Bastidas', 149, 'A'),
(490, 'Agua Viva', 149, 'A'),
(491, 'Sarare', 150, 'A'),
(492, 'Buría', 150, 'A'),
(493, 'Gustavo Vegas León', 150, 'A'),
(494, 'Trinidad Samuel', 151, 'A'),
(495, 'Antonio Díaz', 151, 'A'),
(496, 'Camacaro', 151, 'A'),
(497, 'Castañeda', 151, 'A'),
(498, 'Cecilio Zubillaga', 151, 'A'),
(499, 'Chiquinquirá', 151, 'A'),
(500, 'El Blanco', 151, 'A'),
(501, 'Espinoza de los Monteros', 151, 'A'),
(502, 'Lara', 151, 'A'),
(503, 'Las Mercedes', 151, 'A'),
(504, 'Manuel Morillo', 151, 'A'),
(505, 'Montaña Verde', 151, 'A'),
(506, 'Montes de Oca', 151, 'A'),
(507, 'Torres', 151, 'A'),
(508, 'Heriberto Arroyo', 151, 'A'),
(509, 'Reyes Vargas', 151, 'A'),
(510, 'Altagracia', 151, 'A'),
(511, 'Siquisique', 152, 'A'),
(512, 'Moroturo', 152, 'A'),
(513, 'San Miguel', 152, 'A'),
(514, 'Xaguas', 152, 'A'),
(515, 'Presidente Betancourt', 179, 'A'),
(516, 'Presidente Páez', 179, 'A'),
(517, 'Presidente Rómulo Gallegos', 179, 'A'),
(518, 'Gabriel Picón González', 179, 'A'),
(519, 'Héctor Amable Mora', 179, 'A'),
(520, 'José Nucete Sardi', 179, 'A'),
(521, 'Pulido Méndez', 179, 'A'),
(522, 'La Azulita', 180, 'A'),
(523, 'Santa Cruz de Mora', 181, 'A'),
(524, 'Mesa Bolívar', 181, 'A'),
(525, 'Mesa de Las Palmas', 181, 'A'),
(526, 'Aricagua', 182, 'A'),
(527, 'San Antonio', 182, 'A'),
(528, 'Canagua', 183, 'A'),
(529, 'Capurí', 183, 'A'),
(530, 'Chacantá', 183, 'A'),
(531, 'El Molino', 183, 'A'),
(532, 'Guaimaral', 183, 'A'),
(533, 'Mucutuy', 183, 'A'),
(534, 'Mucuchachí', 183, 'A'),
(535, 'Fernández Peña', 184, 'A'),
(536, 'Matriz', 184, 'A'),
(537, 'Montalbán', 184, 'A'),
(538, 'Acequias', 184, 'A'),
(539, 'Jají', 184, 'A'),
(540, 'La Mesa', 184, 'A'),
(541, 'San José del Sur', 184, 'A'),
(542, 'Tucaní', 185, 'A'),
(543, 'Florencio Ramírez', 185, 'A'),
(544, 'Santo Domingo', 186, 'A'),
(545, 'Las Piedras', 186, 'A'),
(546, 'Guaraque', 187, 'A'),
(547, 'Mesa de Quintero', 187, 'A'),
(548, 'Río Negro', 187, 'A'),
(549, 'Arapuey', 188, 'A'),
(550, 'Palmira', 188, 'A'),
(551, 'San Cristóbal de Torondoy', 189, 'A'),
(552, 'Torondoy', 189, 'A'),
(553, 'Antonio Spinetti Dini', 190, 'A'),
(554, 'Arias', 190, 'A'),
(555, 'Caracciolo Parra Pérez', 190, 'A'),
(556, 'Domingo Peña', 190, 'A'),
(557, 'El Llano', 190, 'A'),
(558, 'Gonzalo Picón Febres', 190, 'A'),
(559, 'Jacinto Plaza', 190, 'A'),
(560, 'Juan Rodríguez Suárez', 190, 'A'),
(561, 'Lasso de la Vega', 190, 'A'),
(562, 'Mariano Picón Salas', 190, 'A'),
(563, 'Milla', 190, 'A'),
(564, 'Osuna Rodríguez', 190, 'A'),
(565, 'Sagrario', 190, 'A'),
(566, 'El Morro', 190, 'A'),
(567, 'Los Nevados', 190, 'A'),
(568, 'Andrés Eloy Blanco', 191, 'A'),
(569, 'La Venta', 191, 'A'),
(570, 'Piñango', 191, 'A'),
(571, 'Timotes', 191, 'A'),
(572, 'Eloy Paredes', 192, 'A'),
(573, 'San Rafael de Alcázar', 192, 'A'),
(574, 'Santa Elena de Arenales', 192, 'A'),
(575, 'Santa María de Caparo', 193, 'A'),
(576, 'Pueblo Llano', 194, 'A'),
(577, 'Cacute', 195, 'A'),
(578, 'La Toma', 195, 'A'),
(579, 'Mucuchíes', 195, 'A'),
(580, 'Mucurubá', 195, 'A'),
(581, 'San Rafael', 195, 'A'),
(582, 'Gerónimo Maldonado', 196, 'A'),
(583, 'Bailadores', 196, 'A'),
(584, 'Tabay', 197, 'A'),
(585, 'Chiguará', 198, 'A'),
(586, 'Estánquez', 198, 'A'),
(587, 'Lagunillas', 198, 'A'),
(588, 'La Trampa', 198, 'A'),
(589, 'Pueblo Nuevo del Sur', 198, 'A'),
(590, 'San Juan', 198, 'A'),
(591, 'El Amparo', 199, 'A'),
(592, 'El Llano', 199, 'A'),
(593, 'San Francisco', 199, 'A'),
(594, 'Tovar', 199, 'A'),
(595, 'Independencia', 200, 'A'),
(596, 'María de la Concepción Palacios Blanco', 200, 'A'),
(597, 'Nueva Bolivia', 200, 'A'),
(598, 'Santa Apolonia', 200, 'A'),
(599, 'Caño El Tigre', 201, 'A'),
(600, 'Zea', 201, 'A'),
(601, 'Aragüita', 223, 'A'),
(602, 'Arévalo González', 223, 'A'),
(603, 'Capaya', 223, 'A'),
(604, 'Caucagua', 223, 'A'),
(605, 'Panaquire', 223, 'A'),
(606, 'Ribas', 223, 'A'),
(607, 'El Café', 223, 'A'),
(608, 'Marizapa', 223, 'A'),
(609, 'Cumbo', 224, 'A'),
(610, 'San José de Barlovento', 224, 'A'),
(611, 'El Cafetal', 225, 'A'),
(612, 'Las Minas', 225, 'A'),
(613, 'Nuestra Señora del Rosario', 225, 'A'),
(614, 'Higuerote', 226, 'A'),
(615, 'Curiepe', 226, 'A'),
(616, 'Tacarigua de Brión', 226, 'A'),
(617, 'Mamporal', 227, 'A'),
(618, 'Carrizal', 228, 'A'),
(619, 'Chacao', 229, 'A'),
(620, 'Charallave', 230, 'A'),
(621, 'Las Brisas', 230, 'A'),
(622, 'El Hatillo', 231, 'A'),
(623, 'Altagracia de la Montaña', 232, 'A'),
(624, 'Cecilio Acosta', 232, 'A'),
(625, 'Los Teques', 232, 'A'),
(626, 'El Jarillo', 232, 'A'),
(627, 'San Pedro', 232, 'A'),
(628, 'Tácata', 232, 'A'),
(629, 'Paracotos', 232, 'A'),
(630, 'Cartanal', 233, 'A'),
(631, 'Santa Teresa del Tuy', 233, 'A'),
(632, 'La Democracia', 234, 'A'),
(633, 'Ocumare del Tuy', 234, 'A'),
(634, 'Santa Bárbara', 234, 'A'),
(635, 'San Antonio de los Altos', 235, 'A'),
(636, 'Río Chico', 236, 'A'),
(637, 'El Guapo', 236, 'A'),
(638, 'Tacarigua de la Laguna', 236, 'A'),
(639, 'Paparo', 236, 'A'),
(640, 'San Fernando del Guapo', 236, 'A'),
(641, 'Santa Lucía del Tuy', 237, 'A'),
(642, 'Cúpira', 238, 'A'),
(643, 'Machurucuto', 238, 'A'),
(644, 'Guarenas', 239, 'A'),
(645, 'San Antonio de Yare', 240, 'A'),
(646, 'San Francisco de Yare', 240, 'A'),
(647, 'Leoncio Martínez', 241, 'A'),
(648, 'Petare', 241, 'A'),
(649, 'Caucagüita', 241, 'A'),
(650, 'Filas de Mariche', 241, 'A'),
(651, 'La Dolorita', 241, 'A'),
(652, 'Cúa', 242, 'A'),
(653, 'Nueva Cúa', 242, 'A'),
(654, 'Guatire', 243, 'A'),
(655, 'Bolívar', 243, 'A'),
(656, 'San Antonio de Maturín', 258, 'A'),
(657, 'San Francisco de Maturín', 258, 'A'),
(658, 'Aguasay', 259, 'A'),
(659, 'Caripito', 260, 'A'),
(660, 'El Guácharo', 261, 'A'),
(661, 'La Guanota', 261, 'A'),
(662, 'Sabana de Piedra', 261, 'A'),
(663, 'San Agustín', 261, 'A'),
(664, 'Teresen', 261, 'A'),
(665, 'Caripe', 261, 'A'),
(666, 'Areo', 262, 'A'),
(667, 'Capital Cedeño', 262, 'A'),
(668, 'San Félix de Cantalicio', 262, 'A'),
(669, 'Viento Fresco', 262, 'A'),
(670, 'El Tejero', 263, 'A'),
(671, 'Punta de Mata', 263, 'A'),
(672, 'Chaguaramas', 264, 'A'),
(673, 'Las Alhuacas', 264, 'A'),
(674, 'Tabasca', 264, 'A'),
(675, 'Temblador', 264, 'A'),
(676, 'Alto de los Godos', 265, 'A'),
(677, 'Boquerón', 265, 'A'),
(678, 'Las Cocuizas', 265, 'A'),
(679, 'La Cruz', 265, 'A'),
(680, 'San Simón', 265, 'A'),
(681, 'El Corozo', 265, 'A'),
(682, 'El Furrial', 265, 'A'),
(683, 'Jusepín', 265, 'A'),
(684, 'La Pica', 265, 'A'),
(685, 'San Vicente', 265, 'A'),
(686, 'Aparicio', 266, 'A'),
(687, 'Aragua de Maturín', 266, 'A'),
(688, 'Chaguamal', 266, 'A'),
(689, 'El Pinto', 266, 'A'),
(690, 'Guanaguana', 266, 'A'),
(691, 'La Toscana', 266, 'A'),
(692, 'Taguaya', 266, 'A'),
(693, 'Cachipo', 267, 'A'),
(694, 'Quiriquire', 267, 'A'),
(695, 'Santa Bárbara', 268, 'A'),
(696, 'Barrancas', 269, 'A'),
(697, 'Los Barrancos de Fajardo', 269, 'A'),
(698, 'Uracoa', 270, 'A'),
(699, 'Antolín del Campo', 271, 'A'),
(700, 'Arismendi', 272, 'A'),
(701, 'García', 273, 'A'),
(702, 'Francisco Fajardo', 273, 'A'),
(703, 'Bolívar', 274, 'A'),
(704, 'Guevara', 274, 'A'),
(705, 'Matasiete', 274, 'A'),
(706, 'Santa Ana', 274, 'A'),
(707, 'Sucre', 274, 'A'),
(708, 'Aguirre', 275, 'A'),
(709, 'Maneiro', 275, 'A'),
(710, 'Adrián', 276, 'A'),
(711, 'Juan Griego', 276, 'A'),
(712, 'Yaguaraparo', 276, 'A'),
(713, 'Porlamar', 277, 'A'),
(714, 'San Francisco de Macanao', 278, 'A'),
(715, 'Boca de Río', 278, 'A'),
(716, 'Tubores', 279, 'A'),
(717, 'Los Baleales', 279, 'A'),
(718, 'Vicente Fuentes', 280, 'A'),
(719, 'Villalba', 280, 'A'),
(720, 'San Juan Bautista', 281, 'A'),
(721, 'Zabala', 281, 'A'),
(722, 'Capital Araure', 283, 'A'),
(723, 'Río Acarigua', 283, 'A'),
(724, 'Capital Esteller', 284, 'A'),
(725, 'Uveral', 284, 'A'),
(726, 'Guanare', 285, 'A'),
(727, 'Córdoba', 285, 'A'),
(728, 'San José de la Montaña', 285, 'A'),
(729, 'San Juan de Guanaguanare', 285, 'A'),
(730, 'Virgen de la Coromoto', 285, 'A'),
(731, 'Guanarito', 286, 'A'),
(732, 'Trinidad de la Capilla', 286, 'A'),
(733, 'Divina Pastora', 286, 'A'),
(734, 'Monseñor José Vicente de Unda', 287, 'A'),
(735, 'Peña Blanca', 287, 'A'),
(736, 'Capital Ospino', 288, 'A'),
(737, 'Aparición', 288, 'A'),
(738, 'La Estación', 288, 'A'),
(739, 'Páez', 289, 'A'),
(740, 'Payara', 289, 'A'),
(741, 'Pimpinela', 289, 'A'),
(742, 'Ramón Peraza', 289, 'A'),
(743, 'Papelón', 290, 'A'),
(744, 'Caño Delgadito', 290, 'A'),
(745, 'San Genaro de Boconoito', 291, 'A'),
(746, 'Antolín Tovar', 291, 'A'),
(747, 'San Rafael de Onoto', 292, 'A'),
(748, 'Santa Fe', 292, 'A'),
(749, 'Thermo Morles', 292, 'A'),
(750, 'Santa Rosalía', 293, 'A'),
(751, 'Florida', 293, 'A'),
(752, 'Sucre', 294, 'A'),
(753, 'Concepción', 294, 'A'),
(754, 'San Rafael de Palo Alzado', 294, 'A'),
(755, 'Uvencio Antonio Velásquez', 294, 'A'),
(756, 'San José de Saguaz', 294, 'A'),
(757, 'Villa Rosa', 294, 'A'),
(758, 'Turén', 295, 'A'),
(759, 'Canelones', 295, 'A'),
(760, 'Santa Cruz', 295, 'A'),
(761, 'San Isidro Labrador', 295, 'A'),
(762, 'Mariño', 296, 'A'),
(763, 'Rómulo Gallegos', 296, 'A'),
(764, 'San José de Aerocuar', 297, 'A'),
(765, 'Tavera Acosta', 297, 'A'),
(766, 'Río Caribe', 298, 'A'),
(767, 'Antonio José de Sucre', 298, 'A'),
(768, 'El Morro de Puerto Santo', 298, 'A'),
(769, 'Puerto Santo', 298, 'A'),
(770, 'San Juan de las Galdonas', 298, 'A'),
(771, 'El Pilar', 299, 'A'),
(772, 'El Rincón', 299, 'A'),
(773, 'General Francisco Antonio Váquez', 299, 'A'),
(774, 'Guaraúnos', 299, 'A'),
(775, 'Tunapuicito', 299, 'A'),
(776, 'Unión', 299, 'A'),
(777, 'Santa Catalina', 300, 'A'),
(778, 'Santa Rosa', 300, 'A'),
(779, 'Santa Teresa', 300, 'A'),
(780, 'Bolívar', 300, 'A'),
(781, 'Maracapana', 300, 'A'),
(782, 'Libertad', 302, 'A'),
(783, 'El Paujil', 302, 'A'),
(784, 'Yaguaraparo', 302, 'A'),
(785, 'Cruz Salmerón Acosta', 303, 'A'),
(786, 'Chacopata', 303, 'A'),
(787, 'Manicuare', 303, 'A'),
(788, 'Tunapuy', 304, 'A'),
(789, 'Campo Elías', 304, 'A'),
(790, 'Irapa', 305, 'A'),
(791, 'Campo Claro', 305, 'A'),
(792, 'Maraval', 305, 'A'),
(793, 'San Antonio de Irapa', 305, 'A'),
(794, 'Soro', 305, 'A'),
(795, 'Mejía', 306, 'A'),
(796, 'Cumanacoa', 307, 'A'),
(797, 'Arenas', 307, 'A'),
(798, 'Aricagua', 307, 'A'),
(799, 'Cogollar', 307, 'A'),
(800, 'San Fernando', 307, 'A'),
(801, 'San Lorenzo', 307, 'A'),
(802, 'Villa Frontado (Muelle de Cariaco)', 308, 'A'),
(803, 'Catuaro', 308, 'A'),
(804, 'Rendón', 308, 'A'),
(805, 'San Cruz', 308, 'A'),
(806, 'Santa María', 308, 'A'),
(807, 'Altagracia', 309, 'A'),
(808, 'Santa Inés', 309, 'A'),
(809, 'Valentín Valiente', 309, 'A'),
(810, 'Ayacucho', 309, 'A'),
(811, 'San Juan', 309, 'A'),
(812, 'Raúl Leoni', 309, 'A'),
(813, 'Gran Mariscal', 309, 'A'),
(814, 'Cristóbal Colón', 310, 'A'),
(815, 'Bideau', 310, 'A'),
(816, 'Punta de Piedras', 310, 'A'),
(817, 'Güiria', 310, 'A'),
(818, 'Andrés Bello', 341, 'A'),
(819, 'Antonio Rómulo Costa', 342, 'A'),
(820, 'Ayacucho', 343, 'A'),
(821, 'Rivas Berti', 343, 'A'),
(822, 'San Pedro del Río', 343, 'A'),
(823, 'Bolívar', 344, 'A'),
(824, 'Palotal', 344, 'A'),
(825, 'General Juan Vicente Gómez', 344, 'A'),
(826, 'Isaías Medina Angarita', 344, 'A'),
(827, 'Cárdenas', 345, 'A'),
(828, 'Amenodoro Ángel Lamus', 345, 'A'),
(829, 'La Florida', 345, 'A'),
(830, 'Córdoba', 346, 'A'),
(831, 'Fernández Feo', 347, 'A'),
(832, 'Alberto Adriani', 347, 'A'),
(833, 'Santo Domingo', 347, 'A'),
(834, 'Francisco de Miranda', 348, 'A'),
(835, 'García de Hevia', 349, 'A'),
(836, 'Boca de Grita', 349, 'A'),
(837, 'José Antonio Páez', 349, 'A'),
(838, 'Guásimos', 350, 'A'),
(839, 'Independencia', 351, 'A'),
(840, 'Juan Germán Roscio', 351, 'A'),
(841, 'Román Cárdenas', 351, 'A'),
(842, 'Jáuregui', 352, 'A'),
(843, 'Emilio Constantino Guerrero', 352, 'A'),
(844, 'Monseñor Miguel Antonio Salas', 352, 'A'),
(845, 'José María Vargas', 353, 'A'),
(846, 'Junín', 354, 'A'),
(847, 'La Petrólea', 354, 'A'),
(848, 'Quinimarí', 354, 'A'),
(849, 'Bramón', 354, 'A'),
(850, 'Libertad', 355, 'A'),
(851, 'Cipriano Castro', 355, 'A'),
(852, 'Manuel Felipe Rugeles', 355, 'A'),
(853, 'Libertador', 356, 'A'),
(854, 'Doradas', 356, 'A'),
(855, 'Emeterio Ochoa', 356, 'A'),
(856, 'San Joaquín de Navay', 356, 'A'),
(857, 'Lobatera', 357, 'A'),
(858, 'Constitución', 357, 'A'),
(859, 'Michelena', 358, 'A'),
(860, 'Panamericano', 359, 'A'),
(861, 'La Palmita', 359, 'A'),
(862, 'Pedro María Ureña', 360, 'A'),
(863, 'Nueva Arcadia', 360, 'A'),
(864, 'Delicias', 361, 'A'),
(865, 'Pecaya', 361, 'A'),
(866, 'Samuel Darío Maldonado', 362, 'A'),
(867, 'Boconó', 362, 'A'),
(868, 'Hernández', 362, 'A'),
(869, 'La Concordia', 363, 'A'),
(870, 'San Juan Bautista', 363, 'A'),
(871, 'Pedro María Morantes', 363, 'A'),
(872, 'San Sebastián', 363, 'A'),
(873, 'Dr. Francisco Romero Lobo', 363, 'A'),
(874, 'Seboruco', 364, 'A'),
(875, 'Simón Rodríguez', 365, 'A'),
(876, 'Sucre', 366, 'A'),
(877, 'Eleazar López Contreras', 366, 'A'),
(878, 'San Pablo', 366, 'A'),
(879, 'Torbes', 367, 'A'),
(880, 'Uribante', 368, 'A'),
(881, 'Cárdenas', 368, 'A'),
(882, 'Juan Pablo Peñalosa', 368, 'A'),
(883, 'Potosí', 368, 'A'),
(884, 'San Judas Tadeo', 369, 'A'),
(885, 'Araguaney', 370, 'A'),
(886, 'El Jaguito', 370, 'A'),
(887, 'La Esperanza', 370, 'A'),
(888, 'Santa Isabel', 370, 'A'),
(889, 'Boconó', 371, 'A'),
(890, 'El Carmen', 371, 'A'),
(891, 'Mosquey', 371, 'A'),
(892, 'Ayacucho', 371, 'A'),
(893, 'Burbusay', 371, 'A'),
(894, 'General Ribas', 371, 'A'),
(895, 'Guaramacal', 371, 'A'),
(896, 'Vega de Guaramacal', 371, 'A'),
(897, 'Monseñor Jáuregui', 371, 'A'),
(898, 'Rafael Rangel', 371, 'A'),
(899, 'San Miguel', 371, 'A'),
(900, 'San José', 371, 'A'),
(901, 'Sabana Grande', 372, 'A'),
(902, 'Cheregüé', 372, 'A'),
(903, 'Granados', 372, 'A'),
(904, 'Arnoldo Gabaldón', 373, 'A'),
(905, 'Bolivia', 373, 'A'),
(906, 'Carrillo', 373, 'A'),
(907, 'Cegarra', 373, 'A'),
(908, 'Chejendé', 373, 'A'),
(909, 'Manuel Salvador Ulloa', 373, 'A'),
(910, 'San José', 373, 'A'),
(911, 'Carache', 374, 'A'),
(912, 'La Concepción', 374, 'A'),
(913, 'Cuicas', 374, 'A'),
(914, 'Panamericana', 374, 'A'),
(915, 'Santa Cruz', 374, 'A'),
(916, 'Escuque', 375, 'A'),
(917, 'La Unión', 375, 'A'),
(918, 'Santa Rita', 375, 'A'),
(919, 'Sabana Libre', 375, 'A'),
(920, 'El Socorro', 376, 'A'),
(921, 'Los Caprichos', 376, 'A'),
(922, 'Antonio José de Sucre', 376, 'A'),
(923, 'Campo Elías', 377, 'A'),
(924, 'Arnoldo Gabaldón', 377, 'A'),
(925, 'Santa Apolonia', 378, 'A'),
(926, 'El Progreso', 378, 'A'),
(927, 'La Ceiba', 378, 'A'),
(928, 'Tres de Febrero', 378, 'A'),
(929, 'El Dividive', 379, 'A'),
(930, 'Agua Santa', 379, 'A'),
(931, 'Agua Caliente', 379, 'A'),
(932, 'El Cenizo', 379, 'A'),
(933, 'Valerita', 379, 'A'),
(934, 'Monte Carmelo', 380, 'A'),
(935, 'Buena Vista', 380, 'A'),
(936, 'Santa María del Horcón', 380, 'A'),
(937, 'Motatán', 381, 'A'),
(938, 'El Baño', 381, 'A'),
(939, 'Jalisco', 381, 'A'),
(940, 'Pampán', 382, 'A'),
(941, 'Flor de Patria', 382, 'A'),
(942, 'La Paz', 382, 'A'),
(943, 'Santa Ana', 382, 'A'),
(944, 'Pampanito', 383, 'A'),
(945, 'La Concepción', 383, 'A'),
(946, 'Pampanito II', 383, 'A'),
(947, 'Betijoque', 384, 'A'),
(948, 'José Gregorio Hernández', 384, 'A'),
(949, 'La Pueblita', 384, 'A'),
(950, 'Los Cedros', 384, 'A'),
(951, 'Carvajal', 385, 'A'),
(952, 'Campo Alegre', 385, 'A'),
(953, 'Antonio Nicolás Briceño', 385, 'A'),
(954, 'José Leonardo Suárez', 385, 'A'),
(955, 'Sabana de Mendoza', 386, 'A'),
(956, 'Junín', 386, 'A'),
(957, 'Valmore Rodríguez', 386, 'A'),
(958, 'El Paraíso', 386, 'A'),
(959, 'Andrés Linares', 387, 'A'),
(960, 'Chiquinquirá', 387, 'A'),
(961, 'Cristóbal Mendoza', 387, 'A'),
(962, 'Cruz Carrillo', 387, 'A'),
(963, 'Matriz', 387, 'A'),
(964, 'Monseñor Carrillo', 387, 'A'),
(965, 'Tres Esquinas', 387, 'A'),
(966, 'Cabimbú', 388, 'A'),
(967, 'Jajó', 388, 'A'),
(968, 'La Mesa de Esnujaque', 388, 'A'),
(969, 'Santiago', 388, 'A'),
(970, 'Tuñame', 388, 'A'),
(971, 'La Quebrada', 388, 'A'),
(972, 'Juan Ignacio Montilla', 389, 'A'),
(973, 'La Beatriz', 389, 'A'),
(974, 'La Puerta', 389, 'A'),
(975, 'Mendoza del Valle de Momboy', 389, 'A'),
(976, 'Mercedes Díaz', 389, 'A'),
(977, 'San Luis', 389, 'A'),
(978, 'Caraballeda', 390, 'A'),
(979, 'Carayaca', 390, 'A'),
(980, 'Carlos Soublette', 390, 'A'),
(981, 'Caruao Chuspa', 390, 'A'),
(982, 'Catia La Mar', 390, 'A'),
(983, 'El Junko', 390, 'A'),
(984, 'La Guaira', 390, 'A'),
(985, 'Macuto', 390, 'A'),
(986, 'Maiquetía', 390, 'A'),
(987, 'Naiguatá', 390, 'A'),
(988, 'Urimare', 390, 'A'),
(989, 'Arístides Bastidas', 391, 'A'),
(990, 'Bolívar', 392, 'A'),
(991, 'Chivacoa', 407, 'A'),
(992, 'Campo Elías', 407, 'A'),
(993, 'Cocorote', 408, 'A'),
(994, 'Independencia', 409, 'A'),
(995, 'José Antonio Páez', 410, 'A'),
(996, 'La Trinidad', 411, 'A'),
(997, 'Manuel Monge', 412, 'A'),
(998, 'Salóm', 413, 'A'),
(999, 'Temerla', 413, 'A'),
(1000, 'Nirgua', 413, 'A'),
(1001, 'San Andrés', 414, 'A'),
(1002, 'Yaritagua', 414, 'A'),
(1003, 'San Javier', 415, 'A'),
(1004, 'Albarico', 415, 'A'),
(1005, 'San Felipe', 415, 'A'),
(1006, 'Sucre', 416, 'A'),
(1007, 'Urachiche', 417, 'A'),
(1008, 'El Guayabo', 418, 'A'),
(1009, 'Farriar', 418, 'A'),
(1010, 'Isla de Toas', 441, 'A'),
(1011, 'Monagas', 441, 'A'),
(1012, 'San Timoteo', 442, 'A'),
(1013, 'General Urdaneta', 442, 'A'),
(1014, 'Libertador', 442, 'A'),
(1015, 'Marcelino Briceño', 442, 'A'),
(1016, 'Pueblo Nuevo', 442, 'A'),
(1017, 'Manuel Guanipa Matos', 442, 'A'),
(1018, 'Ambrosio', 443, 'A'),
(1019, 'Carmen Herrera', 443, 'A'),
(1020, 'La Rosa', 443, 'A'),
(1021, 'Germán Ríos Linares', 443, 'A'),
(1022, 'San Benito', 443, 'A'),
(1023, 'Rómulo Betancourt', 443, 'A'),
(1024, 'Jorge Hernández', 443, 'A'),
(1025, 'Punta Gorda', 443, 'A'),
(1026, 'Arístides Calvani', 443, 'A'),
(1027, 'Encontrados', 444, 'A'),
(1028, 'Udón Pérez', 444, 'A'),
(1029, 'Moralito', 445, 'A'),
(1030, 'San Carlos del Zulia', 445, 'A'),
(1031, 'Santa Cruz del Zulia', 445, 'A'),
(1032, 'Santa Bárbara', 445, 'A'),
(1033, 'Urribarrí', 445, 'A'),
(1034, 'Carlos Quevedo', 446, 'A'),
(1035, 'Francisco Javier Pulgar', 446, 'A'),
(1036, 'Simón Rodríguez', 446, 'A'),
(1037, 'Guamo-Gavilanes', 446, 'A'),
(1038, 'La Concepción', 448, 'A'),
(1039, 'San José', 448, 'A'),
(1040, 'Mariano Parra León', 448, 'A'),
(1041, 'José Ramón Yépez', 448, 'A'),
(1042, 'Jesús María Semprún', 449, 'A'),
(1043, 'Barí', 449, 'A'),
(1044, 'Concepción', 450, 'A'),
(1045, 'Andrés Bello', 450, 'A'),
(1046, 'Chiquinquirá', 450, 'A'),
(1047, 'El Carmelo', 450, 'A'),
(1048, 'Potreritos', 450, 'A'),
(1049, 'Libertad', 451, 'A'),
(1050, 'Alonso de Ojeda', 451, 'A'),
(1051, 'Venezuela', 451, 'A'),
(1052, 'Eleazar López Contreras', 451, 'A'),
(1053, 'Campo Lara', 451, 'A'),
(1054, 'Bartolomé de las Casas', 452, 'A'),
(1055, 'Libertad', 452, 'A'),
(1056, 'Río Negro', 452, 'A'),
(1057, 'San José de Perijá', 452, 'A'),
(1058, 'San Rafael', 453, 'A'),
(1059, 'La Sierrita', 453, 'A'),
(1060, 'Las Parcelas', 453, 'A'),
(1061, 'Luis de Vicente', 453, 'A'),
(1062, 'Monseñor Marcos Sergio Godoy', 453, 'A'),
(1063, 'Ricaurte', 453, 'A'),
(1064, 'Tamare', 453, 'A'),
(1065, 'Antonio Borjas Romero', 454, 'A'),
(1066, 'Bolívar', 454, 'A'),
(1067, 'Cacique Mara', 454, 'A'),
(1068, 'Carracciolo Parra Pérez', 454, 'A'),
(1069, 'Cecilio Acosta', 454, 'A'),
(1070, 'Cristo de Aranza', 454, 'A'),
(1071, 'Coquivacoa', 454, 'A'),
(1072, 'Chiquinquirá', 454, 'A'),
(1073, 'Francisco Eugenio Bustamante', 454, 'A'),
(1074, 'Idelfonzo Vásquez', 454, 'A'),
(1075, 'Juana de Ávila', 454, 'A'),
(1076, 'Luis Hurtado Higuera', 454, 'A'),
(1077, 'Manuel Dagnino', 454, 'A'),
(1078, 'Olegario Villalobos', 454, 'A'),
(1079, 'Raúl Leoni', 454, 'A'),
(1080, 'Santa Lucía', 454, 'A'),
(1081, 'Venancio Pulgar', 454, 'A'),
(1082, 'San Isidro', 454, 'A'),
(1083, 'Altagracia', 455, 'A'),
(1084, 'Faría', 455, 'A'),
(1085, 'Ana María Campos', 455, 'A'),
(1086, 'San Antonio', 455, 'A'),
(1087, 'San José', 455, 'A'),
(1088, 'Donaldo García', 456, 'A'),
(1089, 'El Rosario', 456, 'A'),
(1090, 'Sixto Zambrano', 456, 'A'),
(1091, 'San Francisco', 457, 'A'),
(1092, 'El Bajo', 457, 'A'),
(1093, 'Domitila Flores', 457, 'A'),
(1094, 'Francisco Ochoa', 457, 'A'),
(1095, 'Los Cortijos', 457, 'A'),
(1096, 'Marcial Hernández', 457, 'A'),
(1097, 'Santa Rita', 458, 'A'),
(1098, 'El Mene', 458, 'A'),
(1099, 'Pedro Lucas Urribarrí', 458, 'A'),
(1100, 'José Cenobio Urribarrí', 458, 'A'),
(1101, 'Rafael Maria Baralt', 459, 'A'),
(1102, 'Manuel Manrique', 459, 'A'),
(1103, 'Rafael Urdaneta', 459, 'A'),
(1104, 'Bobures', 460, 'A'),
(1105, 'Gibraltar', 460, 'A'),
(1106, 'Heras', 460, 'A'),
(1107, 'Monseñor Arturo Álvarez', 460, 'A'),
(1108, 'Rómulo Gallegos', 460, 'A'),
(1109, 'El Batey', 460, 'A'),
(1110, 'Rafael Urdaneta', 461, 'A'),
(1111, 'La Victoria', 461, 'A'),
(1112, 'Raúl Cuenca', 461, 'A'),
(1113, 'Sinamaica', 447, 'A'),
(1114, 'Alta Guajira', 447, 'A'),
(1115, 'Elías Sánchez Rubio', 447, 'A'),
(1116, 'Guajira', 447, 'A'),
(1117, 'Altagracia', 462, 'A'),
(1118, 'Antímano', 462, 'A'),
(1119, 'Caricuao', 462, 'A'),
(1120, 'Catedral', 462, 'A'),
(1121, 'Coche', 462, 'A'),
(1122, 'El Junquito', 462, 'A'),
(1123, 'El Paraíso', 462, 'A'),
(1124, 'El Recreo', 462, 'A'),
(1125, 'El Valle', 462, 'A'),
(1126, 'La Candelaria', 462, 'A'),
(1127, 'La Pastora', 462, 'A'),
(1128, 'La Vega', 462, 'A'),
(1129, 'Macarao', 462, 'A'),
(1130, 'San Agustín', 462, 'A'),
(1131, 'San Bernardino', 462, 'A'),
(1132, 'San José', 462, 'A'),
(1133, 'San Juan', 462, 'A'),
(1134, 'San Pedro', 462, 'A'),
(1135, 'Santa Rosalía', 462, 'A'),
(1136, 'Santa Teresa', 462, 'A'),
(1137, 'Sucre (Catia)', 462, 'A'),
(1138, '23 de enero', 462, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_rol`
--

CREATE TABLE `t_rol` (
  `codigo` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `t_rol`
--

INSERT INTO `t_rol` (`codigo`, `nombre`) VALUES
(1, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_usuario`
--

CREATE TABLE `t_usuario` (
  `usuario` varchar(30) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `nacionalidad` char(1) NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `pregunta_seguridad` varchar(100) NOT NULL,
  `respuesta_seguridad` varchar(255) NOT NULL,
  `dias_cambios_c` int(11) NOT NULL,
  `intentos` int(11) NOT NULL,
  `codigo_rol` int(11) NOT NULL,
  `estatus` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `t_usuario`
--

INSERT INTO `t_usuario` (`usuario`, `contrasena`, `nacionalidad`, `cedula`, `pregunta_seguridad`, `respuesta_seguridad`, `dias_cambios_c`, `intentos`, `codigo_rol`, `estatus`) VALUES
('00000000', '$2y$10$otIExl5ApgiNyK3DGGSuN.ZsGDUVgVx0lHSvPjFiTuIaHvO.1EBRy', 'V', '00000000', 'Número predeterminado', '$2y$10$otIExl5ApgiNyK3DGGSuN.ZsGDUVgVx0lHSvPjFiTuIaHvO.1EBRy', 0, 0, 1, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_vista`
--

CREATE TABLE `t_vista` (
  `codigo` int(11) NOT NULL,
  `codigo_modulo` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `enlace` varchar(60) NOT NULL,
  `posicion` int(2) NOT NULL,
  `icono` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `t_vista`
--

INSERT INTO `t_vista` (`codigo`, `codigo_modulo`, `nombre`, `enlace`, `posicion`, `icono`) VALUES
(1, 1, 'Informe social', 'informe_social', 1, 'fas fa-file-word'),
(2, 1, 'Aprendiz', 'aprendiz', 2, 'fas fa-user-graduate'),
(3, 8, 'Facilitadores', 'facilitador', 6, 'fas fa-chalkboard-teacher'),
(4, 4, 'Empresas', 'empresa', 1, 'fas fa-industry'),
(5, 4, 'Actividad económica', 'actividad_economica', 2, 'fas fa-wallet'),
(6, 8, 'Ocupación', 'ocupacion', 7, 'fas fa-briefcase'),
(7, 8, 'Oficio aprendiz', 'oficio', 3, 'fas fa-graduation-cap'),
(9, 8, 'Asignatura', 'asignatura', 5, 'fas fa-book'),
(10, 5, 'Rol de usuario', 'rol', 2, 'fas fa-user-tag'),
(11, 5, 'Módulo sistema', 'modulo_sistema', 3, 'fas fa-sitemap'),
(12, 5, 'Vista del sistema', 'vista_sistema', 4, 'far fa-window-maximize'),
(13, 5, 'Usuario', 'usuario', 5, 'fas fa-users'),
(14, 6, 'Datos personales', 'datos_personales', 1, 'fas fa-id-card'),
(15, 6, 'Seguridad', 'seguridad', 2, 'fas fa-user-shield'),
(16, 7, 'Asistencias', 'asistencias', 1, 'fas fa-clipboard-list'),
(17, 7, 'Notas', 'notas', 2, 'fas fa-address-book'),
(18, 5, 'Base de datos', 'respaldo_db', 1, 'fas fa-database'),
(19, 8, 'Módulos en curso', 'modulo_curso', 1, 'fas fa-chalkboard'),
(20, 8, 'Asignaturas en curso', 'asignatura_curso', 2, 'fas fa-book-reader'),
(21, 4, 'Cargo contacto', 'cargo_contacto', 3, 'fas fa-id-card-alt'),
(22, 8, 'Módulo / Unidad', 'modulo', 4, 'fas fa-sitemap');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `td_asignatura`
--
ALTER TABLE `td_asignatura`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `fk_modulo_curso_asignatura_idx` (`codigo_modulo`),
  ADD KEY `fk_td_asignatura_asignatura_idx` (`codigo_asignatura`),
  ADD KEY `fk_datos_facilitador_tdasignatura_idx` (`nacionalidad_facilitador`,`cedula_facilitador`);

--
-- Indices de la tabla `td_contacto`
--
ALTER TABLE `td_contacto`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `fk_contacto_cargo_idx` (`codigo_cargo`),
  ADD KEY `fk_empresa_contacto_idx` (`rif`),
  ADD KEY `fk_persona_contacto_idx` (`nacionalidad`,`cedula`);

--
-- Indices de la tabla `td_facilitador_asig`
--
ALTER TABLE `td_facilitador_asig`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `fk_asignatura_facilitador_idx` (`codigo_asignatura`),
  ADD KEY `fk_dfacilitador_facilitador_idx` (`nacionalidad_f`,`cedula_f`);

--
-- Indices de la tabla `td_modulo`
--
ALTER TABLE `td_modulo`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `fk_oficio_tdmodulo_idx` (`codigo_oficio`),
  ADD KEY `fk_modulo_tdmodulo_idx` (`codigo_modulo`);

--
-- Indices de la tabla `td_rol_modulo`
--
ALTER TABLE `td_rol_modulo`
  ADD KEY `fk_rol_modulo_idx` (`codigo_rol`),
  ADD KEY `fk_detalles_modulo_idx` (`codigo_modulo`);

--
-- Indices de la tabla `td_rol_vista`
--
ALTER TABLE `td_rol_vista`
  ADD KEY `fk_detalles_rol_servicio_idx` (`codigo_rol`),
  ADD KEY `fk_detalles_servicio_rol_idx` (`codigo_vista`);

--
-- Indices de la tabla `t_actividad_economica`
--
ALTER TABLE `t_actividad_economica`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `t_asignatura`
--
ALTER TABLE `t_asignatura`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `t_asistencia`
--
ALTER TABLE `t_asistencia`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `fk_td_asignatura_asistencia_idx` (`codigo_asignatura`),
  ADD KEY `fk_aprendiz_asistencia_idx` (`numero_ficha`);

--
-- Indices de la tabla `t_bitacora`
--
ALTER TABLE `t_bitacora`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `fk_usuario_bitacora_idx` (`usuario`);

--
-- Indices de la tabla `t_cargo`
--
ALTER TABLE `t_cargo`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `t_ciudad`
--
ALTER TABLE `t_ciudad`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `fk_estado_ciudad_idx` (`codigo_estado`);

--
-- Indices de la tabla `t_contrasenas`
--
ALTER TABLE `t_contrasenas`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `fk_usuario_contrasenas_idx` (`usuario`);

--
-- Indices de la tabla `t_datos_hogar`
--
ALTER TABLE `t_datos_hogar`
  ADD KEY `fk_datos_personales_datos_hogar_idx` (`nacionalidad`,`cedula`);

--
-- Indices de la tabla `t_datos_personales`
--
ALTER TABLE `t_datos_personales`
  ADD PRIMARY KEY (`nacionalidad`,`cedula`),
  ADD KEY `fk_ciudad_datos_personales_idx` (`codigo_ciudad`),
  ADD KEY `fk_ocupacion_datos_personales_idx` (`codigo_ocupacion`),
  ADD KEY `fk_parroqui_datos_personales_idx` (`codigo_parroquia`),
  ADD KEY `fk_municipio_datos_personales_idx` (`codigo_municipio`),
  ADD KEY `fk_ciudad_n_datos_personales_idx` (`codigo_ciudad_n`);

--
-- Indices de la tabla `t_documentos`
--
ALTER TABLE `t_documentos`
  ADD PRIMARY KEY (`numero_doc`),
  ADD KEY `fk_datos_personales_documentos_idx` (`nacionalidad`,`cedula`);

--
-- Indices de la tabla `t_empresa`
--
ALTER TABLE `t_empresa`
  ADD PRIMARY KEY (`rif`),
  ADD KEY `fk_ciudad_empresa_idx` (`codigo_ciudad`),
  ADD KEY `fk_actividad_economica_empresa_idx` (`codigo_actividad`);

--
-- Indices de la tabla `t_estado`
--
ALTER TABLE `t_estado`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `t_familia`
--
ALTER TABLE `t_familia`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `fk_aprendiz_tfamiliar_idx` (`nacionalidad`,`cedula`),
  ADD KEY `fk_familiar_tfamiliar_idx` (`nacionalidad_f`,`cedula_f`);

--
-- Indices de la tabla `t_ficha_aprendiz`
--
ALTER TABLE `t_ficha_aprendiz`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `fk_empresa_actual_ficha_aprendiz_idx` (`empresa_actual`),
  ADD KEY `fk_informe_social_ficha_aprendiz_idx` (`numero_informe`),
  ADD KEY `fk_ficha_anterior_ficha_aprendiz_idx` (`ficha_anterior`);

--
-- Indices de la tabla `t_gestion_dinero`
--
ALTER TABLE `t_gestion_dinero`
  ADD KEY `fk_informe_social_gestion_dinero_idx` (`numero_informe`);

--
-- Indices de la tabla `t_informe_social`
--
ALTER TABLE `t_informe_social`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `fk_oficio_informe_social_idx` (`codigo_oficio`),
  ADD KEY `fk_datos_aprendiz_informe_social_idx` (`nacionalidad_aprendiz`,`cedula_aprendiz`),
  ADD KEY `fk_datos_facilitador_informe_social_idx` (`nacionalidad_fac`,`cedula_facilitador`);

--
-- Indices de la tabla `t_justificativo`
--
ALTER TABLE `t_justificativo`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `fk_justificativo_asistencia_idx` (`numero_asistencia`);

--
-- Indices de la tabla `t_modulo`
--
ALTER TABLE `t_modulo`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `fk_oficio_modulo_idx` (`codigo_oficio`);

--
-- Indices de la tabla `t_modulo_asig`
--
ALTER TABLE `t_modulo_asig`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `fk_modulo_td_modulo_asig_idx` (`codigo_modulo`),
  ADD KEY `fk_asignatura_td_modulo_asig_idx` (`codigo_asignatura`);

--
-- Indices de la tabla `t_modulo_sistema`
--
ALTER TABLE `t_modulo_sistema`
  ADD PRIMARY KEY (`codigo`,`posicion`);

--
-- Indices de la tabla `t_municipio`
--
ALTER TABLE `t_municipio`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `fk_estado_municipio_idx` (`codigo_estado`);

--
-- Indices de la tabla `t_nota`
--
ALTER TABLE `t_nota`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `fk_nota_asignatura_idx` (`codigo_asignatura`),
  ADD KEY `fk_aprendiz_asignatura_idx` (`numero_ficha`);

--
-- Indices de la tabla `t_ocupacion`
--
ALTER TABLE `t_ocupacion`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `t_oficio`
--
ALTER TABLE `t_oficio`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `t_oficio_modulo`
--
ALTER TABLE `t_oficio_modulo`
  ADD KEY `fk_oficio_oficio_modl_idx` (`codigo_oficio`),
  ADD KEY `fk_modulo_oficio_modl_idx` (`codigo_modulo`);

--
-- Indices de la tabla `t_parroquia`
--
ALTER TABLE `t_parroquia`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `fk_municipio_parroqui_idx` (`codigo_municipio`);

--
-- Indices de la tabla `t_rol`
--
ALTER TABLE `t_rol`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `t_usuario`
--
ALTER TABLE `t_usuario`
  ADD PRIMARY KEY (`usuario`),
  ADD KEY `fk_datos_personales_usuario_idx` (`nacionalidad`,`cedula`),
  ADD KEY `fk_rol_usuario_idx` (`codigo_rol`);

--
-- Indices de la tabla `t_vista`
--
ALTER TABLE `t_vista`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `fk_modulo_servicio_idx` (`codigo_modulo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `td_asignatura`
--
ALTER TABLE `td_asignatura`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `td_contacto`
--
ALTER TABLE `td_contacto`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `td_facilitador_asig`
--
ALTER TABLE `td_facilitador_asig`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `td_modulo`
--
ALTER TABLE `td_modulo`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `td_rol_vista`
--
ALTER TABLE `td_rol_vista`
  MODIFY `codigo_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `t_actividad_economica`
--
ALTER TABLE `t_actividad_economica`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_asignatura`
--
ALTER TABLE `t_asignatura`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `t_asistencia`
--
ALTER TABLE `t_asistencia`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_bitacora`
--
ALTER TABLE `t_bitacora`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_cargo`
--
ALTER TABLE `t_cargo`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_ciudad`
--
ALTER TABLE `t_ciudad`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=523;

--
-- AUTO_INCREMENT de la tabla `t_contrasenas`
--
ALTER TABLE `t_contrasenas`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_documentos`
--
ALTER TABLE `t_documentos`
  MODIFY `numero_doc` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_estado`
--
ALTER TABLE `t_estado`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `t_familia`
--
ALTER TABLE `t_familia`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_ficha_aprendiz`
--
ALTER TABLE `t_ficha_aprendiz`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_informe_social`
--
ALTER TABLE `t_informe_social`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_justificativo`
--
ALTER TABLE `t_justificativo`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_modulo`
--
ALTER TABLE `t_modulo`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `t_modulo_asig`
--
ALTER TABLE `t_modulo_asig`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `t_modulo_sistema`
--
ALTER TABLE `t_modulo_sistema`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `t_municipio`
--
ALTER TABLE `t_municipio`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=463;

--
-- AUTO_INCREMENT de la tabla `t_nota`
--
ALTER TABLE `t_nota`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_ocupacion`
--
ALTER TABLE `t_ocupacion`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `t_oficio`
--
ALTER TABLE `t_oficio`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `t_parroquia`
--
ALTER TABLE `t_parroquia`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1139;

--
-- AUTO_INCREMENT de la tabla `t_rol`
--
ALTER TABLE `t_rol`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `t_vista`
--
ALTER TABLE `t_vista`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `td_asignatura`
--
ALTER TABLE `td_asignatura`
  ADD CONSTRAINT `fk_asignatura_tdasignatura` FOREIGN KEY (`codigo_asignatura`) REFERENCES `t_asignatura` (`codigo`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_datos_facilitador_tdasignatura` FOREIGN KEY (`nacionalidad_facilitador`,`cedula_facilitador`) REFERENCES `t_datos_personales` (`nacionalidad`, `cedula`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tdmodulo_tdasignatura` FOREIGN KEY (`codigo_modulo`) REFERENCES `td_modulo` (`codigo`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `td_contacto`
--
ALTER TABLE `td_contacto`
  ADD CONSTRAINT `fk_contacto_cargo` FOREIGN KEY (`codigo_cargo`) REFERENCES `t_cargo` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_empresa_contacto` FOREIGN KEY (`rif`) REFERENCES `t_empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_persona_contacto` FOREIGN KEY (`nacionalidad`,`cedula`) REFERENCES `t_datos_personales` (`nacionalidad`, `cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `td_facilitador_asig`
--
ALTER TABLE `td_facilitador_asig`
  ADD CONSTRAINT `fk_asignatura_facilitador` FOREIGN KEY (`codigo_asignatura`) REFERENCES `t_asignatura` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dfacilitador_facilitador` FOREIGN KEY (`nacionalidad_f`,`cedula_f`) REFERENCES `t_datos_personales` (`nacionalidad`, `cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `td_modulo`
--
ALTER TABLE `td_modulo`
  ADD CONSTRAINT `fk_modulo_tdmodulo` FOREIGN KEY (`codigo_modulo`) REFERENCES `t_modulo` (`codigo`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_oficio_tdmodulo` FOREIGN KEY (`codigo_oficio`) REFERENCES `t_oficio` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `td_rol_modulo`
--
ALTER TABLE `td_rol_modulo`
  ADD CONSTRAINT `fk_detalles_modulo` FOREIGN KEY (`codigo_modulo`) REFERENCES `t_modulo_sistema` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rol_modulo` FOREIGN KEY (`codigo_rol`) REFERENCES `t_rol` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `td_rol_vista`
--
ALTER TABLE `td_rol_vista`
  ADD CONSTRAINT `fk_detalles_rol_servicio` FOREIGN KEY (`codigo_rol`) REFERENCES `t_rol` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalles_rol_vista` FOREIGN KEY (`codigo_vista`) REFERENCES `t_vista` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_asistencia`
--
ALTER TABLE `t_asistencia`
  ADD CONSTRAINT `fk_aprendiz_asistencia` FOREIGN KEY (`numero_ficha`) REFERENCES `t_ficha_aprendiz` (`numero`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tdasignatura_asistencia` FOREIGN KEY (`codigo_asignatura`) REFERENCES `td_asignatura` (`codigo`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_bitacora`
--
ALTER TABLE `t_bitacora`
  ADD CONSTRAINT `fk_usuario_bitacora` FOREIGN KEY (`usuario`) REFERENCES `t_usuario` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_ciudad`
--
ALTER TABLE `t_ciudad`
  ADD CONSTRAINT `fk_estado_ciudad` FOREIGN KEY (`codigo_estado`) REFERENCES `t_estado` (`codigo`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_contrasenas`
--
ALTER TABLE `t_contrasenas`
  ADD CONSTRAINT `fk_usuario_contrasenas` FOREIGN KEY (`usuario`) REFERENCES `t_usuario` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_datos_hogar`
--
ALTER TABLE `t_datos_hogar`
  ADD CONSTRAINT `fk_datos_personales_datos_hogar` FOREIGN KEY (`nacionalidad`,`cedula`) REFERENCES `t_datos_personales` (`nacionalidad`, `cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_datos_personales`
--
ALTER TABLE `t_datos_personales`
  ADD CONSTRAINT `fk_ciudad_datos_personales` FOREIGN KEY (`codigo_ciudad`) REFERENCES `t_ciudad` (`codigo`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ciudad_n_datos_personales` FOREIGN KEY (`codigo_ciudad_n`) REFERENCES `t_ciudad` (`codigo`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_municipio_datos_personales` FOREIGN KEY (`codigo_municipio`) REFERENCES `t_municipio` (`codigo`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ocupacion_datos_personales` FOREIGN KEY (`codigo_ocupacion`) REFERENCES `t_ocupacion` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_parroqui_datos_personales` FOREIGN KEY (`codigo_parroquia`) REFERENCES `t_parroquia` (`codigo`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_documentos`
--
ALTER TABLE `t_documentos`
  ADD CONSTRAINT `fk_datos_personales_documentos` FOREIGN KEY (`nacionalidad`,`cedula`) REFERENCES `t_datos_personales` (`nacionalidad`, `cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_empresa`
--
ALTER TABLE `t_empresa`
  ADD CONSTRAINT `fk_actividad_economica_empresa` FOREIGN KEY (`codigo_actividad`) REFERENCES `t_actividad_economica` (`codigo`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ciudad_empresa` FOREIGN KEY (`codigo_ciudad`) REFERENCES `t_ciudad` (`codigo`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_familia`
--
ALTER TABLE `t_familia`
  ADD CONSTRAINT `fk_aprendiz_tfamiliar` FOREIGN KEY (`nacionalidad`,`cedula`) REFERENCES `t_datos_personales` (`nacionalidad`, `cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_familiar_tfamiliar` FOREIGN KEY (`nacionalidad_f`,`cedula_f`) REFERENCES `t_datos_personales` (`nacionalidad`, `cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_ficha_aprendiz`
--
ALTER TABLE `t_ficha_aprendiz`
  ADD CONSTRAINT `fk_empresa_actual_ficha_aprendiz` FOREIGN KEY (`empresa_actual`) REFERENCES `t_empresa` (`rif`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ficha_anterior_ficha_aprendiz` FOREIGN KEY (`ficha_anterior`) REFERENCES `t_ficha_aprendiz` (`numero`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_informe_social_ficha_aprendiz` FOREIGN KEY (`numero_informe`) REFERENCES `t_informe_social` (`numero`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_gestion_dinero`
--
ALTER TABLE `t_gestion_dinero`
  ADD CONSTRAINT `fk_informe_social_gestion_dinero` FOREIGN KEY (`numero_informe`) REFERENCES `t_informe_social` (`numero`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_informe_social`
--
ALTER TABLE `t_informe_social`
  ADD CONSTRAINT `fk_datos_aprendiz_informe_social` FOREIGN KEY (`nacionalidad_aprendiz`,`cedula_aprendiz`) REFERENCES `t_datos_personales` (`nacionalidad`, `cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_datos_facilitador_informe_social` FOREIGN KEY (`nacionalidad_fac`,`cedula_facilitador`) REFERENCES `t_datos_personales` (`nacionalidad`, `cedula`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_oficio_informe_social` FOREIGN KEY (`codigo_oficio`) REFERENCES `t_oficio` (`codigo`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_justificativo`
--
ALTER TABLE `t_justificativo`
  ADD CONSTRAINT `fk_justificativo_asistencia` FOREIGN KEY (`numero_asistencia`) REFERENCES `t_asistencia` (`numero`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_modulo`
--
ALTER TABLE `t_modulo`
  ADD CONSTRAINT `fk_oficio_modulo` FOREIGN KEY (`codigo_oficio`) REFERENCES `t_oficio` (`codigo`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_modulo_asig`
--
ALTER TABLE `t_modulo_asig`
  ADD CONSTRAINT `fk_asignatura_td_modulo_asig` FOREIGN KEY (`codigo_asignatura`) REFERENCES `t_asignatura` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_modulo_td_modulo_asig` FOREIGN KEY (`codigo_modulo`) REFERENCES `t_modulo` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_municipio`
--
ALTER TABLE `t_municipio`
  ADD CONSTRAINT `fk_estado_municipio` FOREIGN KEY (`codigo_estado`) REFERENCES `t_estado` (`codigo`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_nota`
--
ALTER TABLE `t_nota`
  ADD CONSTRAINT `fk_aprendiz_asignatura` FOREIGN KEY (`numero_ficha`) REFERENCES `t_ficha_aprendiz` (`numero`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nota_asignatura` FOREIGN KEY (`codigo_asignatura`) REFERENCES `td_asignatura` (`codigo`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_oficio_modulo`
--
ALTER TABLE `t_oficio_modulo`
  ADD CONSTRAINT `fk_modulo_oficio_modl` FOREIGN KEY (`codigo_modulo`) REFERENCES `t_modulo` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_oficio_oficio_modl` FOREIGN KEY (`codigo_oficio`) REFERENCES `t_oficio` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_parroquia`
--
ALTER TABLE `t_parroquia`
  ADD CONSTRAINT `fk_municipio_parroqui` FOREIGN KEY (`codigo_municipio`) REFERENCES `t_municipio` (`codigo`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_usuario`
--
ALTER TABLE `t_usuario`
  ADD CONSTRAINT `fk_datos_personales_usuario` FOREIGN KEY (`nacionalidad`,`cedula`) REFERENCES `t_datos_personales` (`nacionalidad`, `cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rol_usuario` FOREIGN KEY (`codigo_rol`) REFERENCES `t_rol` (`codigo`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `t_vista`
--
ALTER TABLE `t_vista`
  ADD CONSTRAINT `fk_modulo_vista` FOREIGN KEY (`codigo_modulo`) REFERENCES `t_modulo_sistema` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
