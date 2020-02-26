-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-02-2020 a las 13:12:07
-- Versión del servidor: 10.4.10-MariaDB
-- Versión de PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_inces`
--

--
-- Volcado de datos para la tabla `td_rol_modulo`
--

INSERT INTO `td_rol_modulo` (`codigo_rol`, `codigo_modulo`) VALUES
(1, 1),
(1, 8),
(1, 7),
(1, 3),
(1, 4),
(1, 5),
(1, 6);

--
-- Volcado de datos para la tabla `td_rol_vista`
--

INSERT INTO `td_rol_vista` (`codigo_rol`, `codigo_vista`, `registrar`, `modificar`, `act_desc`, `eliminar`) VALUES
(1, 1, 1, 1, 1, 1),
(1, 2, 1, 1, 1, 1),
(1, 3, 1, 1, 1, 1),
(1, 6, 1, 1, 1, 1),
(1, 7, 1, 1, 1, 1),
(1, 8, 1, 1, 1, 1),
(1, 9, 1, 1, 1, 1),
(1, 16, 1, 1, 1, 1),
(1, 17, 1, 1, 1, 1),
(1, 4, 1, 1, 1, 1),
(1, 5, 1, 1, 1, 1),
(1, 10, 1, 1, 1, 1),
(1, 11, 1, 1, 1, 1),
(1, 12, 1, 1, 1, 1),
(1, 13, 1, 1, 1, 1),
(1, 18, 1, 1, 1, 1),
(1, 14, 1, 1, 1, 1),
(1, 15, 1, 1, 1, 1);

--
-- Volcado de datos para la tabla `t_rol`
--

INSERT INTO `t_rol` (`codigo`, `nombre`) VALUES
(1, 'Administrador');

--
-- Volcado de datos para la tabla `t_usuario`
--

INSERT INTO `t_usuario` (`usuario`, `contrasena`, `nacionalidad`, `cedula`, `pregunta_seguridad`, `respuesta_seguridad`, `codigo_rol`, `estatus`) VALUES
('00000000', '$2y$10$otIExl5ApgiNyK3DGGSuN.ZsGDUVgVx0lHSvPjFiTuIaHvO.1EBRy', 'V', '26791966', 'Número predeterminado', '$2y$10$otIExl5ApgiNyK3DGGSuN.ZsGDUVgVx0lHSvPjFiTuIaHvO.1EBRy', 1, 'A');

--
-- Volcado de datos para la tabla `t_vista`
--

INSERT INTO `t_vista` (`codigo`, `codigo_modulo`, `nombre`, `enlace`, `posicion`, `icono`) VALUES
(1, 1, 'Informe social', 'informe_social', 1, 'fas fa-file-word'),
(2, 1, 'Aprendiz', 'aprendiz', 2, 'fas fa-user-graduate'),
(3, 8, 'Facilitadores', 'facilitador', 1, 'fas fa-chalkboard-teacher'),
(4, 4, 'Empresas', 'empresa', 1, 'fas fa-industry'),
(5, 4, 'Actividad económica', 'actividad_economica', 2, 'fas fa-wallet'),
(6, 8, 'Ocupación', 'ocupacion', 2, 'fas fa-briefcase'),
(7, 8, 'Oficio aprendiz', 'oficio', 3, 'fas fa-briefcase'),
(8, 8, 'Módulo oficio', 'modulo_oficio', 4, 'fas fa-project-diagram'),
(9, 8, 'Asignatura', 'asignatura', 5, 'fas fa-book'),
(10, 5, 'Rol de usuario', 'rol', 2, 'fas fa-user-tag'),
(11, 5, 'Módulo sistema', 'modulo_sistema', 3, 'fas fa-sitemap'),
(12, 5, 'Vista del sistema', 'vista_sistema', 4, 'far fa-window-maximize'),
(13, 5, 'Usuario', 'usuario', 5, 'fas fa-users'),
(14, 6, 'Datos personales', 'datos_personales', 1, 'fas fa-id-card'),
(15, 6, 'Seguridad', 'seguridad', 2, 'fas fa-user-shield'),
(16, 7, 'Asistencias', 'asistencias', 1, 'fas fa-clipboard-list'),
(17, 7, 'Notas', 'notas', 2, 'fas fa-address-book'),
(18, 5, 'Base de datos', 'respaldo_db', 1, 'fas fa-database');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
