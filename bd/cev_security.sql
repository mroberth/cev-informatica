-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 06-07-2026 a las 21:45:24
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
-- Base de datos: `cev_security`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id` int NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `accion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion_ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id`, `id_usuario`, `accion`, `descripcion`, `direccion_ip`, `user_agent`, `creado_en`) VALUES
(1, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-02 17:54:39'),
(2, 1, 'ACTUALIZAR', 'Usuario actualizado: juan@gmail.com (ID: 2)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-02 17:57:26'),
(3, 1, 'REFRESH_TOKEN', 'Token renovado para: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-02 18:08:40'),
(4, 1, 'LOGOUT', 'Cierre de sesión: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-02 18:13:13'),
(5, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-03 13:26:43'),
(6, 1, 'REFRESH_TOKEN', 'Token renovado para: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-03 13:41:25'),
(7, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-03 13:58:39'),
(8, 1, 'CREAR', 'Rol creado: Superusuario', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-03 13:59:59'),
(9, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-03 14:57:31'),
(10, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-03 15:39:45'),
(11, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-03 16:46:48'),
(12, 1, 'CREAR', 'Usuario creado: ricardo@gmail.com (ID: 8)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-03 16:49:07'),
(13, 1, 'CREAR', 'Módulo creado: PNF', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-03 16:56:46'),
(14, NULL, 'LOGIN_FALLIDO', 'Intento fallido: admin@gmail.com - Correo o contraseña incorrectos', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; es-VE) WindowsPowerShell/5.1.26100.7627', '2026-07-03 17:12:38'),
(15, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-03 17:13:56'),
(16, 1, 'CREAR', 'Unidad Curricular creada: MAT-01 - Matematica I (ID: 1)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-03 17:19:36'),
(17, 1, 'LOGOUT', 'Cierre de sesión: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-03 17:27:00'),
(18, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 14:53:45'),
(19, 1, 'LOGOUT', 'Cierre de sesión: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 15:04:32'),
(20, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 15:04:42'),
(21, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 15:23:51'),
(22, 1, 'REFRESH_TOKEN', 'Token renovado para: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 15:38:13'),
(23, 1, 'LOGOUT', 'Cierre de sesión: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 15:42:07'),
(24, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 19:07:49'),
(25, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 19:24:58'),
(26, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 20:16:35'),
(27, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 20:38:22'),
(28, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 20:55:12'),
(29, 1, 'CREAR', 'Sección creada: IN-1101 (ID: 1)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 20:55:42'),
(30, 1, 'REFRESH_TOKEN', 'Token renovado para: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 21:16:19'),
(31, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 21:48:11'),
(32, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 22:07:54'),
(33, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 22:25:51'),
(34, 1, 'ACTUALIZAR', 'Período académico actualizado: 2026-II (ID: 2)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 22:39:24'),
(35, 1, 'REFRESH_TOKEN', 'Token renovado para: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 22:39:56'),
(36, 1, 'REFRESH_TOKEN', 'Token renovado para: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 22:54:18'),
(37, 1, 'LOGOUT', 'Cierre de sesión: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-04 22:55:45'),
(38, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-06 17:40:18'),
(39, 1, 'LOGOUT', 'Cierre de sesión: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-06 17:40:29'),
(40, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-06 18:52:26'),
(41, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-06 20:06:49'),
(42, 1, 'LOGIN_EXITOSO', 'Inicio de sesión exitoso: admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-06 21:41:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jwt_blacklist`
--

CREATE TABLE `jwt_blacklist` (
  `id` int NOT NULL,
  `token_signature_hash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiracion` timestamp NOT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `jwt_blacklist`
--

INSERT INTO `jwt_blacklist` (`id`, `token_signature_hash`, `expiracion`, `creado_en`) VALUES
(3, 'ff8dabd87dc08810f9c0d932fab5e29cb5b961f3953ad4f87817294ecb806fd7', '2026-06-17 22:52:04', '2026-06-17 18:37:11'),
(4, 'aa81ff742adc67ce109d595bac241b8271df36b969da537d80b24575d19ebdc0', '2026-06-18 21:39:09', '2026-06-18 17:24:21'),
(5, 'ea363905d7dd01c1a008dc33049d64fbb7255a651491cf416aab108eca697a33', '2026-06-19 02:51:13', '2026-06-18 22:36:31'),
(6, '0282d0e9d38abfb584c90b90479ff90dd324e13b2e7cd72d44c5dab8ae32409f', '2026-06-19 04:09:17', '2026-06-18 23:57:05'),
(7, '177be5ab0daaea6156dc877f76e397c20bd6b46cfd6b9ae3ed76527a152c2845', '2026-06-19 06:05:30', '2026-06-19 01:53:03'),
(8, 'ef19cf919d0a0db26c528d6ea7c62f76ef8e372806a941499f362bce33409d9a', '2026-06-21 23:44:09', '2026-06-21 19:29:14'),
(9, 'bfdeadd2778cbf7bca5e1fb18e18d43c20883beb5795ad88d3027b63526c9b89', '2026-06-21 23:52:16', '2026-06-21 19:37:25'),
(10, '3d7a42a015fbe6549f8c6b1e179d6a1507735e57f26f76772432d1b37976470c', '2026-06-22 00:46:58', '2026-06-21 20:34:07'),
(11, 'f23d3332f2779c73983053ddbec6ae52bdd48a7fefb6dd2545aefed7c3b70b44', '2026-06-22 00:49:14', '2026-06-21 20:37:27'),
(12, 'df791100c0e2996140e6d65c6f333f386e6cab9210ae9bbe6fe5347c6ffc84df', '2026-06-22 00:52:39', '2026-06-21 20:49:37'),
(13, '1eb4709e6dbad5e83ac789e4cba6d1ec7349a7b6d351615d1d07156c4a8ec876', '2026-06-22 20:30:48', '2026-06-22 16:15:55'),
(14, 'ce86dc8c68e7beaf4abc56e65166758baf11057cba36af4bdba584fdb3d24689', '2026-06-22 20:40:55', '2026-06-22 16:26:12'),
(15, '1b73f0565b8285b266283766da0dd628c09b9bc4c928a7e964c53a5fbc748405', '2026-06-22 21:00:01', '2026-06-22 16:45:09'),
(16, '4054dd608e0918dbea518608ac7e18513f3356273b9588cdcc377c209415cb66', '2026-06-22 23:49:19', '2026-06-22 19:37:54'),
(17, '906f42944673933c4793f680918297b355b0bcd56a20eaec15753979e339cdc4', '2026-06-23 05:05:18', '2026-06-23 00:50:31'),
(18, 'cf47c49062e027c0c4e230b99906a74f109639d86c994c51b7a03039151f1828', '2026-06-23 19:04:06', '2026-06-23 14:49:21'),
(19, 'd07fe1f8664f4cbf6742bb3e9292b9d1085c94fbad85fceb127aecaea13b0566', '2026-06-23 20:19:20', '2026-06-23 16:17:51'),
(20, '8c703c89d6f6b6c02442bcea7527365008c4be1163e9da89b0186f8960d51ef8', '2026-06-23 20:32:56', '2026-06-23 16:22:28'),
(21, '8a21baf8e53e5f9c0dbeff48d4d690e3976c04bac28293e1af1a2ba836a0a96d', '2026-06-25 19:14:20', '2026-06-25 14:59:35'),
(22, '9245decd4704a4cd8ac51de477aa5d2c0e45ed4e8243d30cf35db5dee1a37c1c', '2026-06-25 19:51:02', '2026-06-25 15:42:19'),
(23, '4d9e2f116fb9cf0814c81becfe8107572c69dff22c3db037424c5ddce739eaad', '2026-06-29 07:21:07', '2026-06-29 03:06:48'),
(24, '73ab5c7ae503cca6905209b7cc9a315c4641ec3ec53f68e46b03f82093781986', '2026-06-29 18:42:58', '2026-06-29 14:28:04'),
(25, '7911997c1bb6aa34875fb5d3c6027aec57379439f3f33ee56db0354cf9232ccd', '2026-06-29 18:52:26', '2026-06-29 14:39:07'),
(26, 'f33d2afa75135c264452c06ae9d4a7c4ef617b8c55501ccce83d7463e7552db1', '2026-06-29 19:16:40', '2026-06-29 15:01:48'),
(27, '4e06ef9908599f07833f9ba9cf2687d7c66a0035bef8255a8ad2cd13e07bc5c0', '2026-06-29 19:24:10', '2026-06-29 15:09:16'),
(28, '039aeb633594bbd7f271f7faabf09bcf7f3b1781970ed319a006819e2d26c02d', '2026-06-29 19:44:40', '2026-06-29 15:29:43'),
(29, 'b9c4bf5c06dd4ab0345b8440163c7cad78f872cb6611ea87ca2ec6797915e2ba', '2026-06-29 20:09:00', '2026-06-29 15:54:25'),
(30, '77672c7046e165b052e7c496d286b0993e135211fb744c12b4ce65f12b24147a', '2026-07-01 00:18:10', '2026-06-30 20:17:59'),
(31, 'c9207e45c73306e42f37970f3cb6b6f13134660c37fc0438196f9ea528a52b37', '2026-07-01 02:31:16', '2026-06-30 22:20:42'),
(32, 'c6d8486094124aba9033e764a1ca7b506df57e4cd54bcb1c4b1b7e23d6315186', '2026-07-01 02:35:50', '2026-06-30 22:20:54'),
(33, 'bb925861c022888581677f2a6e4da70d14b3662c00d421afc0b56d85cc0a2b15', '2026-07-01 02:55:44', '2026-06-30 22:40:52'),
(34, '1b860fef326566174408895fa303b68429cd8c4883f6c287b2251fd75992cf75', '2026-07-01 03:00:19', '2026-06-30 22:47:26'),
(35, '3cb9c9c5984a76fd2193809c746bdbc72061ff97bd4f9962ef5494595d307068', '2026-07-01 03:09:10', '2026-06-30 22:54:39'),
(36, 'ebecd8c5f619044d227d0820df0e0db04656d4eb03e5444dfeab92e08a7e8ee6', '2026-07-02 02:50:54', '2026-07-01 22:44:19'),
(37, 'ff0a5093892dac67968905e8f5d38bbbc87e6a15a56f66510ad85b787c2e97c4', '2026-07-02 20:40:07', '2026-07-02 16:26:01'),
(38, '5ca2ac6f4f1921d4c47b09ffd3e1a1dbf076bbc997faa7808b354cb59b444a6a', '2026-07-02 20:41:25', '2026-07-02 16:29:21'),
(39, 'b73345a6cf4dc133ae97e199be1b8968d1e2c9b7555122ca74913d53a5d62f58', '2026-07-02 20:48:51', '2026-07-02 16:33:57'),
(40, 'cf4989fbf0ccc74236160051859b614640f1216ee2575882c461f467771abc8d', '2026-07-02 20:52:20', '2026-07-02 16:37:35'),
(41, 'baa7aed0bf9e9a997e87ead3feebe42c07462aca3a3eb7ae45ce100104a6b5d4', '2026-07-02 20:52:42', '2026-07-02 16:38:00'),
(42, '5c662b8d9ccf4eafde0219b9c5dd7486680f7a4cf6b68e73e03c804fa214cd41', '2026-07-02 20:53:14', '2026-07-02 16:38:21'),
(43, 'c8635be2d76fd0041ca5532be702bd6c79393c23cbd84dea78216bb61979b9b2', '2026-07-02 21:02:10', '2026-07-02 16:47:34'),
(44, 'c80e28bd37976f81a36b265c653ba963f247229c94907c9f72e7e63af8db5688', '2026-07-02 22:04:15', '2026-07-02 17:54:25'),
(45, 'd45c60628742eefbfecf193d82f908c7973e94af68553997ff8d4fd9fceeea32', '2026-07-02 22:23:40', '2026-07-02 18:13:13'),
(46, '3caf7fcc125af201be518a7ff382719af8df222ae90466c657e45369f3b003ce', '2026-07-03 21:28:56', '2026-07-03 17:27:00'),
(47, 'c4b535de1da24a96652addb14246cb437da52feb9dbe24b9df9300a58ba6f9c5', '2026-07-04 19:08:45', '2026-07-04 15:04:32'),
(48, '3560056748167dea341c92e7774b772cd3f927ba62b075a3ddd8338864d921f5', '2026-07-04 19:53:13', '2026-07-04 15:42:07'),
(49, '39d271de1e873046750780dda961398f451867a3503e0a706af7e9b88c46ea18', '2026-07-05 03:09:18', '2026-07-04 22:55:45'),
(50, '17ae4b583d5526b3ff9e34ff79a0e05c11185afd5a11abe0426f054e95d39eea', '2026-07-06 21:55:18', '2026-07-06 17:40:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id` int NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id`, `nombre`, `descripcion`) VALUES
(1, 'estudiantes', 'Gestión de bachilleres del PNF'),
(2, 'calificaciones', 'Gestión de notas y evaluaciones'),
(3, 'bitacora', 'Auditoría y logs de seguridad'),
(5, 'Unidades Curriculares', 'Gestión de materias del pensum académico'),
(6, 'Secciones', 'Gestión de secciones de clase y periodos lectivos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int NOT NULL,
  `nombre` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `nombre`, `descripcion`) VALUES
(1, 'crear', 'Permite registrar nueva información'),
(2, 'leer', 'Permite visualizar listas y detalles'),
(3, 'editar', 'Permite modificar información existente'),
(4, 'eliminar', 'Permite borrar registros lógicamente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rate_limits`
--

CREATE TABLE `rate_limits` (
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `endpoint` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tokens_actuales` decimal(10,4) NOT NULL,
  `ultima_peticion` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rate_limits`
--

INSERT INTO `rate_limits` (`ip_address`, `endpoint`, `tokens_actuales`, `ultima_peticion`) VALUES
('127.0.0.1', 'api/health', 1.0000, 1782139603),
('127.0.0.1', 'api/user/existe-correo', 1.0000, 1782236982),
('127.0.0.1', 'login', 4.0000, 1783010294),
('127.0.0.1', 'logout', 14.0000, 1783010301),
('127.0.0.1', 'profile', 0.0667, 1781827135),
('127.0.0.1', 'refresh', 14.0000, 1782508766),
('127.0.0.1', 'usuarios', 0.1333, 1782511524),
('127.0.0.1', 'usuarios/check', 1.0000, 1782509222),
('127.0.0.1', 'usuarios/roles', 1.0000, 1782511522),
('127.0.0.1', 'usuarios/{id}', 1.0000, 1782511524);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `refresh_tokens`
--

CREATE TABLE `refresh_tokens` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `token_hash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiracion` timestamp NOT NULL,
  `revocado` tinyint(1) NOT NULL DEFAULT '0',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `refresh_tokens`
--

INSERT INTO `refresh_tokens` (`id`, `usuario_id`, `token_hash`, `expiracion`, `revocado`, `creado_en`) VALUES
(3, 1, '6fae54d3f30c3a90bf7e7752df59931cd82c018f7756fd831715fc3d6ad2ce67', '2026-06-22 20:41:39', 0, '2026-06-15 16:41:39'),
(4, 1, '2d7720b4ba5e308fee4f2ae5476214843caaa9fa6f914ed5ca7ef41940b8efed', '2026-06-24 01:14:46', 1, '2026-06-16 21:14:46'),
(5, 1, 'e7577816487b01aaef3b0aced83b3bec8817e187a7b6563e8c6c29b9678a5bb3', '2026-06-24 01:42:30', 1, '2026-06-16 21:42:30'),
(6, 1, '5a29a79e80ee71679a5a0047f45ac6836650e1454d08b6de372bd81e9cdd18df', '2026-06-24 01:43:00', 1, '2026-06-16 21:43:00'),
(7, 1, 'b46364a18fc82bcf5413ee22c7b3fdaf0464ad0ac6623f9814af19184c6d4e2d', '2026-06-24 01:43:24', 1, '2026-06-16 21:43:24'),
(8, 1, 'c848ca2b57d6f404e3a5532d300e7e37f1d986b23e5890a93b0b6ddff5e787b9', '2026-06-24 01:47:31', 1, '2026-06-16 21:47:31'),
(9, 1, '5491fdaf07f76bca6ee6d947d1197820e449e9c9520a761c5f8d15c828b7364e', '2026-06-24 01:47:52', 1, '2026-06-16 21:47:52'),
(10, 1, '26d9d482e819dacf04f48db0bb2208fefd2f4ec86962bee4c4a37cb797c05fe3', '2026-06-24 01:54:05', 1, '2026-06-16 21:54:05'),
(11, 1, '50ded6563eb5c1c6bc01a867745bd8627b2e4b1252b399bf1fa6a123187f1ab2', '2026-06-24 01:54:42', 1, '2026-06-16 21:54:42'),
(12, 1, '14259d758fd11e11f1ff6f078f893be1aa68a9646ada4dd2392ea28e96daa702', '2026-06-24 01:55:05', 1, '2026-06-16 21:55:05'),
(13, 1, '46da180461ffb60bff9bfc603c566d188c882f8f347ad77ed11dafb8d2426748', '2026-06-24 02:04:06', 1, '2026-06-16 22:04:06'),
(14, 1, '2f0f8d0435c070de85a2e89b25417a9bf5034c36ded321dc06e8c63e9925c08d', '2026-06-24 02:04:16', 1, '2026-06-16 22:04:16'),
(15, 1, 'b6c32869a11b01edf475fd2a6a03040addef288b0a772f39fa634be6765382e9', '2026-06-24 02:16:05', 1, '2026-06-16 22:16:05'),
(16, 1, '984cc7e02c206f69a4e30d98c2d85be75ca748fef654bd903ee63658be5057f6', '2026-06-24 02:31:28', 1, '2026-06-16 22:31:28'),
(17, 1, '71f5d0ddf31ba7ca091720bfb93c341847a20f2e60402b9b49ae4eb4e0a4d9fa', '2026-06-24 02:45:21', 1, '2026-06-16 22:45:21'),
(18, 1, 'd367fee88ed3f7874f4ccef332d59bdc834cf856772807990e8911891b53e013', '2026-06-24 19:58:23', 1, '2026-06-17 15:58:23'),
(19, 1, '43494fdc9ac7c78741e229e70cff4f3e4bb6613c2da29cc52539dca4a40a2899', '2026-06-24 20:27:06', 1, '2026-06-17 16:27:06'),
(20, 1, '4ef1c5dc4a74f4fc38c6c593c9ce804a097977352ec71296f461904086756059', '2026-06-24 20:55:01', 1, '2026-06-17 16:55:01'),
(21, 1, 'd3f8c8b912f77a7d0cad8c02592d0ae393741db1f377579b0baabebf1ad1ea5c', '2026-06-24 20:57:59', 1, '2026-06-17 16:57:59'),
(22, 1, '9d4fd41c70e5789ef63444a4d1f0ee720077894516e142094255f89317b1a1c4', '2026-06-24 20:58:47', 1, '2026-06-17 16:58:47'),
(23, 1, '7708d255ce143f860aa034c1222c36cb078f7d5d77834f9dbb91438f0886a57b', '2026-06-24 21:00:54', 1, '2026-06-17 17:00:54'),
(24, 1, '380ec49d039f071755b2fddf4bfaaafc0754d7d668f88091630dc0fb6136f1a8', '2026-06-24 21:03:10', 1, '2026-06-17 17:03:10'),
(25, 1, '1fd003563d15e5320b0966e82b718d3bf4c2c5a68488583946b213fa724586f0', '2026-06-24 21:58:44', 1, '2026-06-17 17:58:44'),
(26, 1, '8913234f480ba3b33ed489ea21ea908303cca62610c5b0719e6dbe627f335614', '2026-06-24 21:59:44', 1, '2026-06-17 17:59:44'),
(27, 1, '01bad04192767d23e633c6feeaf08bad51adb5cc24a6aed09884ecd7d7fc11b4', '2026-06-24 22:20:44', 1, '2026-06-17 18:20:44'),
(28, 1, 'b3786cb0cf0f0bd893dcafa1f92bf2ac0353743f8ea9ac57e3500afa5968478d', '2026-06-24 22:27:46', 1, '2026-06-17 18:27:46'),
(29, 1, '83456922a09b6ca41076ec3a7fd34f23f20003acfd95d822cfea93fdd864ff9e', '2026-06-24 22:28:30', 1, '2026-06-17 18:28:30'),
(30, 1, 'c67ea96e636256abbf6d092e809bc23cebfe68f31751898df3b932cb8e42be06', '2026-06-24 22:29:38', 1, '2026-06-17 18:29:38'),
(31, 1, 'c520e44b8a9e6ff0b9562d7ed6f7be6ba6c69437af9b1773fe3338afe3eae13a', '2026-06-24 22:34:47', 0, '2026-06-17 18:34:47'),
(32, 1, '55ee068fd53bbf171751b618b39162239b479ef1493c682e7ddc7da519585fd6', '2026-06-24 22:37:04', 1, '2026-06-17 18:37:04'),
(33, 1, '847c2a224b75295934af0d861deb5c52a014ff96ec5676a7827e7df39372b0c2', '2026-06-25 21:24:09', 1, '2026-06-18 17:24:09'),
(34, 1, 'f96b3ff06e631b181ebb1db80709a6b4e5b99c8b736c94eaf8bdc0550e899d9c', '2026-06-25 21:46:31', 0, '2026-06-18 17:46:31'),
(35, 1, 'fccf9738e24f1e811b5add0f5d1fbb658aa05d5650a00a66f7dc204981298d9e', '2026-06-26 02:36:13', 1, '2026-06-18 22:36:13'),
(36, 1, '975f2281f3f1379ffe6beacc55e38657313419096368f206e0f6e0df91867ad9', '2026-06-26 03:54:17', 1, '2026-06-18 23:54:17'),
(37, 1, '7d4860b610e7259a200f60bb5eea48c8914a9fc9b52c6d4f363b509b192ede2f', '2026-06-26 04:04:44', 0, '2026-06-19 00:04:44'),
(38, 1, '5b8fc6aa7680cdf7a0995a4bb4d98772e4b3db9daf4cb4554544507b6462fb95', '2026-06-26 05:50:30', 1, '2026-06-19 01:50:30'),
(39, 1, '5061d05d4c154fc64d28e40b2692172275e4d3c5dff186478fde5d10a84b06ab', '2026-06-26 19:20:34', 0, '2026-06-19 15:20:34'),
(40, 1, '332c3465bed7a4c83e3cdd7c17cd4091c81093b7854f1ee3437d98177604fb47', '2026-06-28 23:29:09', 1, '2026-06-21 19:29:09'),
(41, 1, '1abdb5146af0ca0f50d1839036fc897ac0463ecbae69fa44c02beac53ed43eca', '2026-06-28 23:37:16', 1, '2026-06-21 19:37:16'),
(42, 1, '5d25329d1cc14a92879a1e35b9e1cea81439f883cd8fe558af83e6d22a2c369e', '2026-06-29 00:31:58', 1, '2026-06-21 20:31:58'),
(43, 1, '9d14e07e356bfd2fea81e60c00a13b11e1eba48bd25713a145faf841cf512e9e', '2026-06-29 00:34:11', 0, '2026-06-21 20:34:11'),
(44, 1, '77b78654a0f45f90f1020e0692185d27ebba9e150062ec24f8af23660c473830', '2026-06-29 00:34:13', 0, '2026-06-21 20:34:13'),
(45, 1, '8d1b867e77cb40fc9038ebb3d0416217d65cc8dfd58bf169ce4c8d7718f5af44', '2026-06-29 00:34:14', 1, '2026-06-21 20:34:14'),
(46, 1, '4b23d3475408f1b3fc9f52bb7da61751a54aad6108ffbeaf541e643be4a220ce', '2026-06-29 00:37:39', 1, '2026-06-21 20:37:39'),
(47, 1, 'ea52d9025786c8a70f0b7634ad57e92889154d0818434e21db6c545bd623fbba', '2026-06-29 00:49:44', 0, '2026-06-21 20:49:44'),
(48, 1, 'd11f9ffea2886c7370d7f5d19c0174d993b2da24b0e034cb2104caae69c1ebb7', '2026-06-29 19:43:50', 0, '2026-06-22 15:43:50'),
(49, 1, 'ead26e289dece9dc08c46c82b816c02a71d51140ada75d591942927fde1aa2ce', '2026-06-29 19:44:49', 0, '2026-06-22 15:44:49'),
(50, 1, 'bd38b9e03290b02dffe4931384ba4ae7a021a4aeccaacb74cea6b8c95bdeed4f', '2026-06-29 19:45:02', 0, '2026-06-22 15:45:02'),
(51, 1, '7734439df129a80dad84ea0dbab58aefd792ce33e96e6819c2500ff1b1d3c1a4', '2026-06-29 19:47:17', 0, '2026-06-22 15:47:17'),
(52, 1, 'd5c7fb1c114b8b7991a6863b5fed5148141a6f6bd0d827c42818f58dd780efd9', '2026-06-29 19:47:51', 0, '2026-06-22 15:47:51'),
(53, 1, 'f291e3aea4c9c661162d116f1d57af00db538b84e4d20bd2d76e1d50ba8ea846', '2026-06-29 19:54:07', 0, '2026-06-22 15:54:07'),
(54, 1, '65a54ba11ab1103d8efb60d97fed74df33c5c812cf68239f1edfc92ab781b0a9', '2026-06-29 20:02:26', 0, '2026-06-22 16:02:26'),
(55, 1, '2451841758d5692d3373dd4e22dc0042696686f60086e86a445c4ae34eb42e00', '2026-06-29 20:03:10', 0, '2026-06-22 16:03:10'),
(56, 1, 'c39a0992b97c0bc40a54591059c77f5b6bd1c33c0ccc09ff03bd0d8d2f1b207e', '2026-06-29 20:15:48', 1, '2026-06-22 16:15:48'),
(57, 1, '23202be967b18412d81a0d4672954319a77c0c71f587dd6e04c627cfb98fa227', '2026-06-29 20:25:55', 1, '2026-06-22 16:25:55'),
(59, 1, 'bc4616f9d9cbb09510b36c4a8270ea259fce1a772177928cceb81e44125ba015', '2026-06-29 20:45:01', 1, '2026-06-22 16:45:01'),
(60, 1, '944c0cf2d8c24de4316de415e10ba776f3efaadf67b078655344480631bb7321', '2026-06-29 20:55:45', 0, '2026-06-22 16:55:45'),
(61, 1, '4be84b7b8cfc0477349bd19115160f225e48825830589a673b7ccb5b66ae402c', '2026-06-29 23:34:19', 1, '2026-06-22 19:34:19'),
(62, 1, '082605ec4b07cc873efe65356538dc72d4e7889b8006ad77c4d6b7e210967e7a', '2026-06-30 04:50:18', 1, '2026-06-23 00:50:18'),
(63, 1, '07adf85dad738ae463b93b70d865c5927b2f6b4e07469744abf32685175db73b', '2026-06-30 05:05:14', 1, '2026-06-23 01:05:14'),
(64, 1, '5489d584855ab362901bd3f3703948326951276523ee10a18b48b91dbe412b19', '2026-06-30 05:23:21', 1, '2026-06-23 01:23:21'),
(65, 1, 'dda51e53c659f68099382f013d332641d419f53982bba11a324b25f1cb9981d4', '2026-06-30 18:49:06', 1, '2026-06-23 14:49:06'),
(66, 1, '0f1e925a2fd51a67c2a1d8924bda35f4a77b853a84ed8fda55a2dd38bff96c94', '2026-06-30 19:33:44', 0, '2026-06-23 15:33:44'),
(67, 1, '86ef68544cbb771ab6345c669d0ce0bedbbf821ae71eb9787b973d8eda022c59', '2026-06-30 20:04:20', 1, '2026-06-23 16:04:20'),
(68, 1, '9a8d6a5082740bafe57a180e546c2b76da971a957aa2abb770fd360d2e256207', '2026-06-30 20:17:56', 1, '2026-06-23 16:17:56'),
(69, 1, '5ca28e58277a1abe6ad26a74a32bb4da4bb519ce7c5e19c1715b1b48936c80c3', '2026-06-30 20:22:33', 1, '2026-06-23 16:22:33'),
(70, 1, 'a361d79b0cf489d5b4af5cc5f3d40eb33b8a931ce8931ab24c1c25b043e1cdb4', '2026-06-30 20:45:12', 0, '2026-06-23 16:45:12'),
(71, 1, '3f8135672cd4fa57bc793df6c70446411de762b7f8d5a04032e5184ddcacc0db', '2026-06-30 21:45:50', 0, '2026-06-23 17:45:50'),
(72, 1, '73f160307a5ca02cdaa98d8f423999cb64a21d3602324028e8780110e6fd5e33', '2026-07-02 17:46:39', 1, '2026-06-25 13:46:39'),
(73, 1, 'e9c9f555a986e22e5f65999edd409f72c7554dd22ba8972d5129d46a1aa039d7', '2026-07-02 18:14:00', 0, '2026-06-25 14:14:00'),
(74, 1, '91e89532dfe0fde24992f601addaed288d53665ed4a8cd5593c63893075aa8fe', '2026-07-02 18:21:50', 0, '2026-06-25 14:21:50'),
(75, 1, '2d40fc3776739ffb1d5039f95b17a4252532e8b0495efe14bec490beb5d93f66', '2026-07-02 18:43:15', 1, '2026-06-25 14:43:15'),
(76, 1, 'b9a21459e6b84850edc6b1da80e45beb24658917f2cca601f1833799fca47807', '2026-07-02 18:59:20', 1, '2026-06-25 14:59:20'),
(77, 1, 'a445f2e13853fd66900689fca03d5cf1e4ecea124e8e6c8339b31bd0975e1061', '2026-07-02 18:59:43', 0, '2026-06-25 14:59:43'),
(78, 1, '02964df0ecf3753e184ba433232bb72633cefc53f972f20b00a9ab40c18eb273', '2026-07-02 19:35:57', 1, '2026-06-25 15:35:57'),
(79, 1, 'c9240d56281e2835c6a0a79fd5b5757c0a02cb3dd02ea3e3709cb36b32dc8e41', '2026-07-02 19:36:02', 1, '2026-06-25 15:36:02'),
(80, 1, 'ea0e99bedef13c11c396ba2d76936fa1907b52a397273d5a27bb69cf0f256771', '2026-07-04 00:30:19', 1, '2026-06-26 20:30:19'),
(81, 1, 'accd8b4967b2732e96428d382e64fd86943eb844758cb22e92e2772a8ba8ffa4', '2026-07-04 00:45:36', 1, '2026-06-26 20:45:36'),
(82, 1, '7a933ae5820d6055b4b9fc002cc070ed3a8621cae47ea04d97c19e16c09008f5', '2026-07-04 01:01:25', 1, '2026-06-26 21:01:25'),
(83, 1, 'eb8284d346febacc64d9398bbbde8097ea7dfc6e63f43dbeb1cad686461b409e', '2026-07-04 01:19:26', 0, '2026-06-26 21:19:26'),
(84, 1, 'c1bdca6922280e8b134ac183243abe185288e58e274d2af5c63f894a825b3ab4', '2026-07-04 01:58:55', 0, '2026-06-26 21:58:55'),
(85, 1, '4a5745c86a51a6cf290c524c01f5d7a56a72445b9c18c7ab34f9fcbd3163a93b', '2026-07-06 07:06:07', 1, '2026-06-29 03:06:07'),
(86, 1, '4e73542e963eefa60fdfe461b4c35ee33973f7e459abb5413fe3be9de7823c39', '2026-07-06 18:27:58', 1, '2026-06-29 14:27:58'),
(87, 1, '83976d9d18ba96f0256cbda8f9d30da513e40d2c4c1dfe45ad2cdc535f1101a0', '2026-07-06 18:37:26', 1, '2026-06-29 14:37:26'),
(88, 1, 'e8a17817ee61db1ad31c969840af508a0197cd90ec895a034209a1bfd042ab70', '2026-07-06 18:57:08', 0, '2026-06-29 14:57:08'),
(89, 1, '5d3668b41bc058f69e60992d0c5fee90faeee17ef20f1aad785180c0522afafe', '2026-07-06 19:01:40', 1, '2026-06-29 15:01:40'),
(90, 1, '5e7f5cea0d3792d55f970ed53cb28905f7e0ee1351901b64d13173d5bf9b6b16', '2026-07-06 19:08:47', 0, '2026-06-29 15:08:47'),
(91, 1, '9405c921aebe24b71570aabc2dfc2e8219fff23dc8418437443b5d2934a9f597', '2026-07-06 19:09:10', 1, '2026-06-29 15:09:10'),
(92, 1, '77d68310bafa7b0c13f0983974c16d4abc14451492db380b61a38297de711cae', '2026-07-06 19:18:51', 0, '2026-06-29 15:18:51'),
(93, 1, 'a4ad255ecd0ff5b4107b464e20704e83229f7d99058e503f72b95746da7e3021', '2026-07-06 19:29:40', 1, '2026-06-29 15:29:40'),
(94, 1, 'caa1c3030738293f34750b5bf5c61c8480787a55b02da0157f8f6bd3ee7bdac8', '2026-07-06 19:33:31', 0, '2026-06-29 15:33:31'),
(95, 1, '70ec37e054ceab6affcd04b9d3be257eed3c0d500a00ddaa4f6aa1343ffe8b58', '2026-07-06 19:40:13', 0, '2026-06-29 15:40:13'),
(96, 1, '56288d7ddf72f0f830358244997b2fe82cb7a3f54b903c06082ea7bf4f8f7d4d', '2026-07-06 19:42:46', 0, '2026-06-29 15:42:46'),
(97, 1, '135d400d00d08adfc9505ecb9a7ba19f4fcddde90eb06a8bf248ba6ccfa81f1e', '2026-07-06 19:54:00', 1, '2026-06-29 15:54:00'),
(98, 1, '08a81abd3427281e257fe4362e72809850693f5bed6f5dda590f2f67a1260da8', '2026-07-07 23:41:57', 0, '2026-06-30 19:41:57'),
(99, 1, '123a248000659b5fbfdb93880919373fb9afa521eb48287f7269b5bc82dbafe4', '2026-07-08 00:03:10', 1, '2026-06-30 20:03:10'),
(100, 1, '0d8b522001fd42bd8488f5b68954cbc9e693534a978d441d2ed403d26dff3749', '2026-07-08 00:18:01', 0, '2026-06-30 20:18:01'),
(101, 1, 'd73c0e8f5391464d3afa105df450469958376b333b8bf70de8cfe0edd9dfad89', '2026-07-08 00:40:13', 1, '2026-06-30 20:40:13'),
(102, 1, 'a8deb7ad6e7346fbc533d247b3267c553193daa1866f3c8f9a39b7a1dd9c2506', '2026-07-08 00:54:15', 0, '2026-06-30 20:54:15'),
(103, 1, '868bdb45a3149f93a5181209edbda313cea53fc7c88613f651e44e987ba8366c', '2026-07-08 01:13:21', 1, '2026-06-30 21:13:21'),
(104, 1, 'b7ad97c2324876c062972a9a741a63f6a978a5ca44374114fab1640a4cb7b671', '2026-07-08 01:27:22', 1, '2026-06-30 21:27:22'),
(105, 1, 'dabe5c5921e92e6750a8159b7662844773231df59a3d0945cc98bce692ae14b3', '2026-07-08 01:51:26', 1, '2026-06-30 21:51:26'),
(106, 1, '25d958cd202c402b7fdb691f45f5ece8560298aa8f0e23299744f9ec1d0dfd0d', '2026-07-08 01:51:36', 0, '2026-06-30 21:51:36'),
(107, 1, '529ea5dbcb4f4074c3119f706dfb8a8f7146be76a6dcae8e5a91d77f466b7dc0', '2026-07-08 02:16:16', 1, '2026-06-30 22:16:16'),
(108, 1, '7d4fa65e69e2a35960d5734360b79c8c88e3522273ee8a36f89d4b2ee38709f4', '2026-07-08 02:20:50', 1, '2026-06-30 22:20:50'),
(109, 1, '2a97873549a463bc5ba17614a1d0ccdd481d7b33c420c9d95205352163c27131', '2026-07-08 02:25:47', 0, '2026-06-30 22:25:47'),
(110, 1, '2fc2e74b29d451343b1a57e1503c686588d0659d455b621c4b8a4dd3848b9a92', '2026-07-08 02:40:44', 1, '2026-06-30 22:40:44'),
(111, 1, 'a25e8619b509e803052217b5c7aa0686456ffa8695d174d9856397ed22c42875', '2026-07-08 02:45:19', 1, '2026-06-30 22:45:19'),
(112, 1, '6cd4b1c5efc05139f751fe34f2cd4164de83d7fac0da18ebbeb19058b59efaa8', '2026-07-08 02:54:10', 1, '2026-06-30 22:54:10'),
(113, 1, '493b0ffcefb25d09baacc6fb2c2a743df168e7cca4665dc7bdafca358447c959', '2026-07-08 22:17:15', 1, '2026-07-01 18:17:15'),
(114, 1, 'ee152901b6f3722ec1bc2c2554949bf6b989a7ba56d80c6db4b9acdde449c048', '2026-07-08 22:31:28', 1, '2026-07-01 18:31:28'),
(115, 1, 'c534fabe0e1d10d72db9192fdc4ff24d2e6703c794fa5e8ebb182377a5769f22', '2026-07-08 22:45:31', 0, '2026-07-01 18:45:31'),
(116, 1, '50fcdd7ab73bd7f58b6e4ffcaffe93e7cd5565a4612aa9f7a5526d48e25f902e', '2026-07-08 23:03:15', 1, '2026-07-01 19:03:15'),
(117, 1, 'a5765c751bd753344cd37058db8732cb6ccef3d51bf90eaae651a353732facf1', '2026-07-08 23:17:54', 1, '2026-07-01 19:17:54'),
(118, 1, 'ff8ae067c86fe20d9305fe63383b7268870364e46326baf97f9e1fce2d3a5d98', '2026-07-08 23:40:36', 0, '2026-07-01 19:40:36'),
(119, 1, 'c3bd5d5c8e3f9cee174a757960873253a9274206088dfa0af0e51daaaa0c57c1', '2026-07-08 23:40:43', 0, '2026-07-01 19:40:43'),
(120, 1, '920ca4a0b57573c22c312cfd617e358ee34dd2a8ae1bd6184195966fda97ca63', '2026-07-09 00:06:42', 1, '2026-07-01 20:06:42'),
(121, 1, '95b856af85d4f039302a93047335552bcc315fa97b942d5a21a399e91ed64f73', '2026-07-09 00:35:38', 0, '2026-07-01 20:35:38'),
(122, 1, '2fa91272a3c6c16af2988c8bdf306d433c7f7017a13a96bf83c258d00872cb89', '2026-07-09 00:59:35', 1, '2026-07-01 20:59:35'),
(123, 1, '38b9449c42084702dba8d4b0cd33b984a39476e490c2e81b15194001638b14a6', '2026-07-09 01:13:43', 0, '2026-07-01 21:13:43'),
(124, 1, '4be2a688bff63cf2335209dd5a22cfa3da72cd26fdfbeacd34c71e9c1fb815bc', '2026-07-09 01:32:59', 1, '2026-07-01 21:32:59'),
(125, 1, '4c98110c8d3aaa5a68ba28916e3df6122a02d05351dae6b7ffacee0cec4f9631', '2026-07-09 01:47:05', 1, '2026-07-01 21:47:05'),
(126, 1, 'b4062bae9e31dab78b6f40fb5d242ad0a7053ed0a94f4cbe3463d81fdf844d05', '2026-07-09 02:02:42', 1, '2026-07-01 22:02:42'),
(127, 1, '38801ec7e0b2426ae930396f5295157e5ad6c523bc97ea7d9df7bfb49011d402', '2026-07-09 02:02:43', 1, '2026-07-01 22:02:43'),
(128, 1, 'a86647d293b5914a640bdbdec626a088013aa730fa93940177383f2f50ce2172', '2026-07-09 02:02:43', 0, '2026-07-01 22:02:43'),
(129, 1, 'b49c4db77349a0332b6fa2dd6a918e0434f627baeae56ffd04648e7d79788f5e', '2026-07-09 02:16:56', 1, '2026-07-01 22:16:56'),
(130, 1, '2413189b1ba3f5b324501fe6a438772832cfb202d343e515bc823265260a45eb', '2026-07-09 02:32:51', 0, '2026-07-01 22:32:51'),
(131, 1, '4c7bea4ec9e0424f97a337ea99be83ba664a1c848137d729d1836d2c2173c49c', '2026-07-09 02:35:54', 1, '2026-07-01 22:35:54'),
(132, 1, 'f61c88661872c62ef17986adb4a0507f77f78846ad897475f0218e30a06dc8b4', '2026-07-09 17:47:31', 0, '2026-07-02 13:47:31'),
(133, 1, '211c621cb291e750707043e38f103b1ec8b8b1fd338944192946a6755d5bcd2f', '2026-07-09 18:04:56', 1, '2026-07-02 14:04:56'),
(134, 1, 'ef5c9732620a603536e456eb653f61e45c2c47c2d80f7dfb5758f87f7e4a4895', '2026-07-09 18:19:07', 0, '2026-07-02 14:19:07'),
(135, 1, '7794a884850008f9398660eff3d5fe70741de8e405275dc48b831d56c7f4e5b2', '2026-07-09 18:34:17', 0, '2026-07-02 14:34:17'),
(136, 1, 'cde469f070fa79fe8b939b9dbaa76d8727b580c3df1eff0465eac4f198a02c6c', '2026-07-09 20:25:07', 1, '2026-07-02 16:25:07'),
(137, 1, 'd2f5319b0e153a0de029b4fd05b7205af60a1572c7effbc7dd9905337dce8484', '2026-07-09 20:26:25', 1, '2026-07-02 16:26:25'),
(138, 1, 'd40f015de98af46b86f5202ba36b284d559284c9a3358bab5065dd817cc7fe7a', '2026-07-09 20:33:51', 1, '2026-07-02 16:33:51'),
(139, 1, 'e283e8535a42be1dfbcf368f4d6b896409ad7b563951684541a61f80222a718b', '2026-07-09 20:37:20', 1, '2026-07-02 16:37:20'),
(141, 1, 'cad9a4f6e447e92c1b2365275ffab0d2ac457e8fe8716edaad8a2e135203787c', '2026-07-09 20:38:14', 1, '2026-07-02 16:38:14'),
(142, 1, '67908eab51933e3997e23734651451714736bdc4461e3bf0bc5334a2e03010ac', '2026-07-09 20:47:10', 1, '2026-07-02 16:47:10'),
(143, 1, '0d6c54569a749044509a266df63ba6dc2e6082247c0be7a6b5836a5209007653', '2026-07-09 20:50:35', 1, '2026-07-02 16:50:35'),
(144, 1, '42c16e8eda3ea1674581db0c4861b2a81dbd1a9352eeeb5fb838a4253f0bcc0b', '2026-07-09 21:04:50', 0, '2026-07-02 17:04:50'),
(145, 1, '0660498c7865c1edcf689f214b32e211d04c334731e967fe24b53530d12c47a6', '2026-07-09 21:20:19', 0, '2026-07-02 17:20:19'),
(146, 1, '0b9981d9c2873e0d09e6b25276261dbfc9030071ed29114cd37a138c5bf47021', '2026-07-09 21:49:15', 1, '2026-07-02 17:49:15'),
(147, 1, 'd77bcaa26f1a2ba540c6cba92de19f049fa0c8bb3e02a7d1169ab6c829b6c545', '2026-07-09 21:54:39', 1, '2026-07-02 17:54:39'),
(148, 1, 'aa22df722cce6cfb92b86d921a5f4cab1e87acc4fb29d979f38ec62bf39b05e1', '2026-07-09 22:08:40', 1, '2026-07-02 18:08:40'),
(149, 1, 'eeeadca0e3871ec452732451361cbdeb4a8eb97f340b80ed144c809e82a8f328', '2026-07-10 17:26:43', 1, '2026-07-03 13:26:43'),
(150, 1, '8298b4bd84bc43e89934e6e742ac0525118652ae7e4f5b080cb7d2bf1a2efdfc', '2026-07-10 17:41:25', 0, '2026-07-03 13:41:25'),
(151, 1, 'c8460a007f70ce18f5409d0b0737b347b250f1b18bd31493bdcf09ced8ca9415', '2026-07-10 17:58:39', 0, '2026-07-03 13:58:39'),
(152, 1, '513f66de83f6e7b525dda5735334d3c20b14d762202a0b907229c6c6060be8fa', '2026-07-10 18:57:31', 0, '2026-07-03 14:57:31'),
(153, 1, 'fb41e6c8ae61c155271f6d87637330128dd0c0c7c0b06334fd56ce6313837821', '2026-07-10 19:39:45', 0, '2026-07-03 15:39:45'),
(154, 1, '30842bec33ad667fa057f9e51806fb8dca55097740436a5702f945a47a8e0202', '2026-07-10 20:46:48', 0, '2026-07-03 16:46:48'),
(155, 1, 'c79fcda54f702d6702ca856023761f9defd1207c4cb07edd41d9c1e14e10b0ae', '2026-07-10 21:13:56', 1, '2026-07-03 17:13:56'),
(156, 1, 'e7da55ad9d893c0e67e7f95f5904ba3bcab1efe9426fb59e7f30ef14662c1848', '2026-07-11 18:53:45', 1, '2026-07-04 14:53:45'),
(157, 1, '59fb0a7a92a7e198617a83b29ac42551c2853de69eb8fd35c40667e186926ca7', '2026-07-11 19:04:42', 0, '2026-07-04 15:04:42'),
(158, 1, 'd33b7b67f07e47a8566f377583224913d9a8cadb4a657b28b31816c0976d62fc', '2026-07-11 19:23:51', 1, '2026-07-04 15:23:51'),
(159, 1, 'daeb847d92d620c50973d79e2e5480db138d604cd88cadb27f7114267957c386', '2026-07-11 19:38:13', 1, '2026-07-04 15:38:13'),
(160, 1, '7c6a90088a9f866f7bc6dae10a03e8f6abd3b82ddd13ad6fed975bbcde03ac6d', '2026-07-11 23:07:49', 0, '2026-07-04 19:07:49'),
(161, 1, '0079e93347c067dde1ea03d2b3a86cf662d1a22e5cbcd9d3ae665f9823446cbb', '2026-07-11 23:24:58', 0, '2026-07-04 19:24:58'),
(162, 1, '8cc55823a72b565a6b3f7a7c6698dbd3e91eb3f50922c99911126a615c35ef76', '2026-07-12 00:16:35', 0, '2026-07-04 20:16:35'),
(163, 1, '29988e1ea3b6432a626182ace09986d074909650be8e59671505ce4528927340', '2026-07-12 00:38:22', 0, '2026-07-04 20:38:22'),
(164, 1, '778dcfaf641198de299e3116eb91bcb0a569b02309fb0cc7338c227b38609dbb', '2026-07-12 00:55:12', 1, '2026-07-04 20:55:12'),
(165, 1, 'fa29dd7f7ed74385607e569c355384a8b08307945b49dc57b492f8a15c464b54', '2026-07-12 01:12:48', 1, '2026-07-04 21:12:48'),
(166, 1, 'ca567fa39e6ce79f202673ec5f6f88892bf8305b53839e77d81d122a94a665c2', '2026-07-12 01:16:19', 0, '2026-07-04 21:16:19'),
(167, 1, 'f1ccad8f2d2bfe16ab9c2b62e380db1a630c6c316063b29e01ad1018ee86ec2c', '2026-07-12 01:48:11', 0, '2026-07-04 21:48:11'),
(168, 1, 'c27662da3fee8272d317256bda0dd2e1bfd372d387191df525268593b53058f7', '2026-07-12 02:07:54', 0, '2026-07-04 22:07:54'),
(169, 1, '7036bcc4b4ddeb40b46bbf37ca148645b30d80210be02e97dd075ad57b702a88', '2026-07-12 02:25:51', 1, '2026-07-04 22:25:51'),
(170, 1, 'c342581a5e6789ab465a3407e9cdb6ba56ef20a196bb61646b19157207dc7ec1', '2026-07-12 02:39:56', 1, '2026-07-04 22:39:56'),
(171, 1, '20bad8e9d254c7f2bed8f58d5836b8f7f97c50b3f6c7e59eeeb1aeb3989a7658', '2026-07-12 02:54:18', 1, '2026-07-04 22:54:18'),
(172, 1, '16375d082d579b873eefd320814be6602b14a734a5072b2617a993186565173a', '2026-07-13 21:40:18', 1, '2026-07-06 17:40:18'),
(173, 1, '178fba12e7f3dd1d2a074487f4ad64fe0f5fdb69f67632bbe72b0520db546b31', '2026-07-13 22:52:26', 0, '2026-07-06 18:52:26'),
(174, 1, '4ea53a29057e6898126c023b7a6a4fdbe9589b95d7bfaec4f71ba2db2e5d99b9', '2026-07-14 00:06:49', 0, '2026-07-06 20:06:49'),
(175, 1, 'cdbc35d085c321ce8c52894760229953e761788bd811ce9f433f02a881103e9d', '2026-07-14 01:41:12', 0, '2026-07-06 21:41:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `nombre_rol` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre_rol`, `descripcion`) VALUES
(1, 'Admin', 'Administrador central con acceso total al control de usuarios y configuración.'),
(2, 'Profesor', 'Personal docente con permisos de carga académica y evaluación.'),
(3, 'Estudiante', 'Usuario alumno con acceso a consultas de notas, horarios y entregas.'),
(4, 'Superusuario', 'Usuario por encima del Administrador central, con acceso total a todo el sistema.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_modulo_permiso`
--

CREATE TABLE `rol_modulo_permiso` (
  `id_rol` int NOT NULL,
  `id_modulo` int NOT NULL,
  `id_permiso` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol_modulo_permiso`
--

INSERT INTO `rol_modulo_permiso` (`id_rol`, `id_modulo`, `id_permiso`) VALUES
(1, 1, 1),
(1, 1, 2),
(1, 1, 3),
(1, 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `tipo_cedula` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'V',
  `cedula` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol_id` int NOT NULL,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `tipo_cedula`, `cedula`, `nombre`, `apellido`, `correo`, `telefono`, `password_hash`, `rol_id`, `estado`, `creado_en`, `actualizado_en`) VALUES
(1, 'V', '28281433', 'Roberth', 'Matos', 'admin@gmail.com', '04129298008', '$2y$10$n8qwWPONScIsmRV1QAbbY.RHrR1BM3j4UF5SkN85SM.CBJNMyf0Rm', 1, 'activo', '2026-06-15 16:18:07', '2026-07-03 16:34:46'),
(8, 'V', '28281432', 'Ricardo', 'Dos', 'ricardo@gmail.com', '04129298000', '$2y$10$wLlb5ElQ4J/DPF4RVd6KCu.3gskp6idbkvja3L3rn0x3mqu3eGzmS', 3, 'activo', '2026-07-03 16:49:07', '2026-07-03 16:49:07');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bitacora_usuarios_idx` (`id_usuario`),
  ADD KEY `idx_accion` (`accion`),
  ADD KEY `idx_creado_en` (`creado_en`);

--
-- Indices de la tabla `jwt_blacklist`
--
ALTER TABLE `jwt_blacklist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_signature_hash` (`token_signature_hash`),
  ADD KEY `idx_expiracion` (`expiracion`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `rate_limits`
--
ALTER TABLE `rate_limits`
  ADD PRIMARY KEY (`ip_address`,`endpoint`);

--
-- Indices de la tabla `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_token_hash` (`token_hash`),
  ADD KEY `fk_tokens_usuarios_idx` (`usuario_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `rol_modulo_permiso`
--
ALTER TABLE `rol_modulo_permiso`
  ADD PRIMARY KEY (`id_rol`,`id_modulo`,`id_permiso`),
  ADD KEY `fk_rmp_modulo` (`id_modulo`),
  ADD KEY `fk_rmp_permiso` (`id_permiso`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_correo` (`correo`),
  ADD UNIQUE KEY `uk_cedula` (`tipo_cedula`,`cedula`),
  ADD KEY `fk_usuarios_roles_idx` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `jwt_blacklist`
--
ALTER TABLE `jwt_blacklist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `fk_bitacora_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD CONSTRAINT `fk_tokens_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rol_modulo_permiso`
--
ALTER TABLE `rol_modulo_permiso`
  ADD CONSTRAINT `fk_rmp_modulo` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rmp_permiso` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rmp_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_roles` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
