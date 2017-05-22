-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-05-2017 a las 19:32:15
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `homestead`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canvas`
--

CREATE TABLE `canvas` (
  `canvasId` int(10) UNSIGNED NOT NULL,
  `tw` int(11) UNSIGNED NOT NULL,
  `th` int(11) UNSIGNED NOT NULL,
  `width` int(11) UNSIGNED NOT NULL,
  `height` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `canvas`
--

INSERT INTO `canvas` (`canvasId`, `tw`, `th`, `width`, `height`) VALUES
(12, 64, 64, 13, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `name` varchar(35) DEFAULT NULL,
  `path` varchar(500) NOT NULL,
  `icon` varchar(35) DEFAULT NULL,
  `width` int(10) UNSIGNED NOT NULL,
  `height` int(10) UNSIGNED NOT NULL,
  `emptyTiles` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `categoriaId` int(11) UNSIGNED NOT NULL,
  `tilesetId` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`name`, `path`, `icon`, `width`, `height`, `emptyTiles`, `categoriaId`, `tilesetId`) VALUES
('Terreno', 'img/tilesets/terrain.png', 'terrain', 256, 192, 0, 17, 5),
('Naturaleza', 'img/tilesets/nature.png', 'nature', 256, 192, 0, 18, 5),
('Caminos', 'img/tilesets/roads.png', 'directions', 512, 256, 2, 19, 5),
('Edificios', 'img/tilesets/buildings.png', 'store', 256, 192, 0, 20, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `layers`
--

CREATE TABLE `layers` (
  `layerId` int(11) UNSIGNED NOT NULL,
  `name` varchar(35) DEFAULT NULL,
  `visible` varchar(35) NOT NULL,
  `tilesetId` int(11) UNSIGNED NOT NULL,
  `mapaId` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `layers`
--

INSERT INTO `layers` (`layerId`, `name`, `visible`, `tilesetId`, `mapaId`) VALUES
(1, 'Background', 'visibility', 5, 15),
(2, 'Terreno', 'visibility', 5, 15),
(3, 'Background', 'visibility', 5, 15),
(4, 'Terreno', 'visibility', 5, 15),
(5, 'Background', 'visibility', 5, 15),
(6, 'Terreno', 'visibility', 5, 15),
(7, 'Background', 'visibility', 5, 22),
(8, 'Terreno', 'visibility', 5, 22),
(9, 'Background', 'visibility', 5, 23),
(10, 'Terreno', 'visibility', 5, 23),
(11, 'Background', 'visibility', 5, 24),
(12, 'Terreno', 'visibility', 5, 24),
(13, 'Caminos', 'visibility', 5, 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mapas`
--

CREATE TABLE `mapas` (
  `mapaId` int(11) UNSIGNED NOT NULL,
  `userId` int(10) UNSIGNED NOT NULL,
  `tilesetId` int(11) UNSIGNED NOT NULL,
  `canvasId` int(11) UNSIGNED NOT NULL,
  `token` varchar(32) DEFAULT NULL,
  `link` varchar(70) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `nombre` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `mapas`
--

INSERT INTO `mapas` (`mapaId`, `userId`, `tilesetId`, `canvasId`, `token`, `link`, `descripcion`, `nombre`) VALUES
(13, 2, 5, 12, NULL, NULL, NULL, NULL),
(14, 2, 5, 12, NULL, NULL, NULL, NULL),
(15, 2, 5, 12, NULL, NULL, NULL, NULL),
(16, 2, 5, 12, NULL, NULL, NULL, NULL),
(17, 2, 5, 12, NULL, NULL, NULL, NULL),
(18, 2, 5, 12, NULL, NULL, NULL, NULL),
(19, 2, 5, 12, NULL, NULL, NULL, NULL),
(20, 2, 5, 12, NULL, NULL, NULL, NULL),
(21, 2, 5, 12, NULL, NULL, NULL, NULL),
(22, 2, 5, 12, NULL, NULL, NULL, NULL),
(23, 1, 5, 12, NULL, NULL, NULL, NULL),
(24, 1, 5, 12, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2017_05_14_234842_create_social_accounts_table', 1),
(4, '2017_05_20_000536_entrust_setup_tables', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'create-map-model', 'Crear Mapa Modelo', 'Crea un nuevo Mapa Modelo para todos los usuarios', '2017-05-20 23:04:46', '2017-05-20 23:04:46'),
(2, 'delete-map-model', 'Eliminar Mapa Modelo', 'Elimina un Mapa Modelo existente', '2017-05-20 23:04:46', '2017-05-20 23:04:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator', 'El usuario puede administrar y editar mapas modelo', '2017-05-20 23:04:45', '2017-05-20 23:04:45'),
(2, 'member', 'Miembro', 'El usuario puede guardar y recuperar sus mapas personalizados', '2017-05-20 23:04:45', '2017-05-20 23:04:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_user`
--

CREATE TABLE `role_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `social_accounts`
--

CREATE TABLE `social_accounts` (
  `user_id` int(11) NOT NULL,
  `provider_user_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiles`
--

CREATE TABLE `tiles` (
  `categoriaId` int(11) UNSIGNED NOT NULL,
  `layerId` int(11) UNSIGNED NOT NULL,
  `tileId` int(11) UNSIGNED NOT NULL,
  `coordX` int(11) NOT NULL,
  `coordY` int(11) NOT NULL,
  `tileInCategoria` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tiles`
--

INSERT INTO `tiles` (`categoriaId`, `layerId`, `tileId`, `coordX`, `coordY`, `tileInCategoria`) VALUES
(17, 4, 1, 6, 3, 2),
(17, 4, 2, 3, 2, 2),
(17, 4, 3, 2, 4, 2),
(17, 4, 4, 5, 2, 2),
(17, 4, 5, 5, 1, 2),
(17, 4, 6, 4, 3, 2),
(17, 6, 7, 6, 3, 2),
(17, 6, 8, 3, 2, 2),
(17, 6, 9, 2, 4, 2),
(17, 6, 10, 5, 2, 2),
(17, 6, 11, 5, 1, 2),
(17, 6, 12, 4, 3, 2),
(17, 6, 13, 2, 5, 0),
(17, 6, 14, 4, 6, 0),
(17, 6, 15, 4, 5, 0),
(17, 6, 16, 5, 5, 0),
(17, 6, 17, 9, 5, 0),
(17, 6, 18, 9, 4, 0),
(17, 6, 19, 8, 3, 0),
(17, 6, 20, 5, 4, 0),
(17, 6, 21, 4, 4, 0),
(17, 10, 22, 3, 2, 6),
(17, 10, 23, 3, 1, 6),
(17, 10, 24, 1, 2, 6),
(17, 10, 25, 1, 3, 6),
(17, 10, 26, 6, 3, 6),
(17, 11, 27, 0, 0, 0),
(17, 11, 28, 1, 0, 0),
(17, 11, 29, 2, 0, 0),
(17, 11, 30, 3, 0, 0),
(17, 11, 31, 4, 0, 0),
(17, 11, 32, 5, 0, 1),
(17, 11, 33, 6, 0, 0),
(17, 11, 34, 7, 0, 0),
(17, 11, 35, 8, 0, 0),
(17, 11, 36, 9, 0, 0),
(17, 11, 37, 10, 0, 0),
(17, 11, 38, 11, 0, 0),
(17, 11, 39, 12, 0, 0),
(17, 11, 40, 12, 1, 0),
(17, 11, 41, 11, 1, 0),
(17, 11, 42, 10, 1, 0),
(17, 11, 43, 8, 1, 0),
(17, 11, 44, 7, 1, 0),
(17, 11, 45, 9, 1, 1),
(17, 11, 46, 6, 1, 0),
(17, 11, 47, 5, 1, 0),
(17, 11, 48, 4, 1, 1),
(17, 11, 49, 3, 1, 0),
(17, 11, 50, 2, 1, 1),
(17, 11, 51, 1, 1, 1),
(17, 11, 52, 0, 1, 0),
(17, 11, 53, 0, 2, 0),
(17, 11, 54, 0, 3, 1),
(17, 11, 55, 0, 4, 0),
(17, 11, 56, 0, 5, 0),
(17, 11, 57, 0, 6, 0),
(17, 11, 58, 0, 7, 0),
(17, 11, 59, 0, 8, 1),
(17, 11, 60, 0, 9, 0),
(17, 11, 61, 4, 9, 1),
(17, 11, 62, 6, 9, 0),
(17, 11, 63, 8, 9, 0),
(17, 11, 64, 9, 9, 1),
(17, 11, 65, 10, 9, 0),
(17, 11, 66, 11, 9, 0),
(17, 11, 67, 12, 9, 1),
(17, 11, 68, 12, 8, 0),
(17, 11, 69, 12, 7, 0),
(17, 11, 70, 12, 5, 0),
(17, 11, 71, 12, 4, 0),
(17, 11, 72, 12, 2, 0),
(17, 11, 73, 12, 3, 1),
(17, 11, 74, 12, 6, 1),
(17, 11, 75, 7, 9, 1),
(17, 11, 76, 5, 9, 0),
(17, 11, 77, 2, 9, 0),
(17, 11, 78, 1, 9, 0),
(17, 11, 79, 3, 9, 0),
(17, 11, 80, 1, 7, 0),
(17, 11, 81, 1, 6, 0),
(17, 11, 82, 1, 5, 1),
(17, 11, 83, 1, 4, 0),
(17, 11, 84, 1, 3, 0),
(17, 11, 85, 1, 2, 0),
(17, 11, 86, 2, 8, 1),
(17, 11, 87, 1, 8, 0),
(17, 11, 88, 3, 8, 0),
(17, 11, 89, 4, 8, 0),
(17, 11, 90, 5, 8, 0),
(17, 11, 91, 6, 8, 0),
(17, 11, 92, 7, 8, 0),
(17, 11, 93, 9, 8, 0),
(17, 11, 94, 11, 8, 0),
(17, 11, 95, 10, 8, 0),
(17, 11, 96, 11, 7, 0),
(17, 11, 97, 11, 6, 0),
(17, 11, 98, 11, 5, 0),
(17, 11, 99, 11, 4, 0),
(17, 11, 100, 11, 3, 0),
(17, 11, 101, 11, 2, 0),
(17, 11, 102, 10, 2, 0),
(17, 11, 103, 9, 2, 0),
(17, 11, 104, 8, 2, 0),
(17, 11, 105, 7, 2, 0),
(17, 11, 106, 6, 2, 0),
(17, 11, 107, 5, 2, 1),
(17, 11, 108, 4, 2, 1),
(17, 11, 109, 3, 2, 0),
(17, 11, 110, 2, 3, 1),
(17, 11, 111, 2, 2, 0),
(17, 11, 112, 2, 4, 0),
(17, 11, 113, 2, 5, 0),
(17, 11, 114, 2, 6, 0),
(17, 11, 115, 2, 7, 1),
(17, 11, 116, 4, 7, 0),
(17, 11, 117, 5, 7, 0),
(17, 11, 118, 3, 7, 0),
(17, 11, 119, 7, 7, 1),
(17, 11, 120, 8, 7, 0),
(17, 11, 121, 8, 8, 0),
(17, 11, 122, 9, 7, 0),
(17, 11, 123, 10, 7, 1),
(17, 11, 124, 6, 7, 0),
(17, 11, 125, 9, 6, 0),
(17, 11, 126, 8, 6, 0),
(17, 11, 127, 7, 6, 0),
(17, 11, 128, 5, 6, 0),
(17, 11, 129, 4, 6, 1),
(17, 11, 130, 3, 6, 1),
(17, 11, 131, 3, 5, 0),
(17, 11, 132, 3, 4, 0),
(17, 11, 133, 3, 3, 0),
(17, 11, 134, 4, 3, 0),
(17, 11, 135, 6, 3, 1),
(17, 11, 136, 8, 3, 0),
(17, 11, 137, 10, 3, 0),
(17, 11, 138, 10, 5, 0),
(17, 11, 139, 10, 6, 0),
(17, 11, 140, 10, 4, 0),
(17, 11, 141, 9, 4, 0),
(17, 11, 142, 9, 3, 0),
(17, 11, 143, 8, 5, 0),
(17, 11, 144, 7, 4, 1),
(17, 11, 145, 8, 4, 1),
(17, 11, 146, 7, 5, 1),
(17, 11, 147, 7, 3, 0),
(17, 11, 148, 9, 5, 1),
(17, 11, 149, 6, 5, 1),
(17, 11, 150, 6, 4, 0),
(17, 11, 151, 5, 4, 0),
(17, 11, 152, 5, 3, 0),
(17, 11, 153, 4, 4, 1),
(17, 11, 154, 4, 5, 1),
(17, 11, 155, 6, 6, 0),
(17, 11, 156, 5, 5, 0),
(18, 12, 157, 0, 0, 3),
(18, 12, 158, 1, 0, 3),
(18, 12, 159, 2, 0, 3),
(18, 12, 160, 3, 0, 3),
(18, 12, 161, 4, 0, 3),
(18, 12, 162, 5, 0, 3),
(18, 12, 163, 6, 0, 3),
(18, 12, 164, 7, 0, 3),
(18, 12, 165, 8, 0, 3),
(18, 12, 166, 10, 0, 3),
(18, 12, 167, 12, 0, 3),
(18, 12, 168, 1, 1, 3),
(18, 12, 169, 3, 1, 3),
(18, 12, 170, 5, 1, 3),
(18, 12, 171, 8, 1, 3),
(18, 12, 172, 10, 1, 3),
(18, 12, 173, 11, 1, 3),
(18, 12, 174, 8, 2, 3),
(18, 12, 175, 9, 0, 2),
(18, 12, 176, 11, 0, 2),
(18, 12, 177, 4, 1, 2),
(18, 12, 178, 6, 1, 2),
(18, 12, 179, 9, 1, 2),
(18, 12, 180, 0, 1, 2),
(18, 12, 181, 12, 1, 2),
(18, 12, 182, 3, 2, 1),
(18, 12, 183, 1, 2, 1),
(18, 12, 184, 10, 2, 1),
(18, 12, 185, 9, 2, 1),
(18, 12, 186, 11, 3, 1),
(18, 12, 187, 5, 2, 0),
(18, 12, 188, 0, 2, 0),
(18, 12, 189, 11, 2, 0),
(18, 12, 190, 12, 2, 0),
(18, 12, 191, 6, 2, 0),
(18, 12, 192, 12, 3, 11),
(18, 12, 193, 12, 4, 9),
(18, 12, 194, 11, 4, 10),
(18, 12, 195, 12, 5, 10),
(18, 12, 196, 10, 3, 8),
(18, 12, 197, 10, 4, 8),
(18, 12, 198, 11, 5, 8),
(18, 12, 199, 9, 3, 8),
(18, 12, 200, 11, 6, 8),
(18, 12, 201, 12, 9, 7),
(18, 12, 202, 12, 8, 7),
(18, 12, 203, 11, 8, 7),
(18, 12, 204, 11, 9, 7),
(18, 12, 205, 10, 9, 7),
(18, 12, 206, 12, 7, 7),
(18, 12, 207, 12, 6, 6),
(18, 12, 208, 11, 7, 6),
(18, 12, 209, 10, 8, 5),
(18, 12, 210, 9, 9, 6),
(18, 12, 211, 10, 7, 5),
(18, 12, 212, 9, 8, 5),
(18, 12, 213, 10, 6, 4),
(18, 12, 214, 9, 7, 4),
(18, 12, 215, 2, 1, 1),
(18, 12, 216, 7, 1, 1),
(18, 12, 217, 7, 2, 2),
(19, 13, 218, 0, 7, 28),
(19, 13, 219, 0, 4, 28),
(19, 13, 220, 1, 7, 28),
(19, 13, 221, 1, 4, 28),
(19, 13, 222, 3, 4, 28),
(19, 13, 223, 4, 4, 28),
(19, 13, 224, 3, 7, 28),
(19, 13, 225, 4, 7, 28),
(19, 13, 226, 6, 7, 28),
(19, 13, 227, 7, 7, 28),
(19, 13, 228, 6, 4, 28),
(19, 13, 229, 7, 4, 28),
(19, 13, 230, 8, 5, 6),
(19, 13, 231, 8, 6, 6),
(19, 13, 232, 8, 8, 6),
(19, 13, 233, 8, 9, 6),
(19, 13, 234, 5, 8, 6),
(19, 13, 235, 5, 9, 6),
(19, 13, 236, 2, 8, 6),
(19, 13, 237, 2, 9, 6),
(19, 13, 238, 2, 6, 6),
(19, 13, 239, 2, 5, 6),
(19, 13, 240, 5, 5, 6),
(19, 13, 241, 5, 6, 6),
(19, 13, 242, 2, 7, 21),
(19, 13, 243, 5, 7, 21),
(19, 13, 244, 8, 7, 20),
(19, 13, 245, 8, 4, 18),
(19, 13, 246, 2, 4, 1),
(19, 13, 247, 5, 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tilesets`
--

CREATE TABLE `tilesets` (
  `tilesetId` int(11) UNSIGNED NOT NULL,
  `tw` int(11) UNSIGNED NOT NULL,
  `th` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tilesets`
--

INSERT INTO `tilesets` (`tilesetId`, `tw`, `th`) VALUES
(5, 64, 64);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@builder.com', '$2y$10$ftdiIv/mTMdHE1i9Uj0rxOgp8Hh2H1.GUq2JSdIkOhWBtRWcKX.Ci', NULL, '2017-05-20 23:04:45', '2017-05-20 23:04:45'),
(2, 'Pepita La Pistolera', 'lucasmenchi@hotmail.com.ar', '$2y$10$2oCfbMawpccx.RSiESvwGORjqvP6C7o0KT1zh/yYEnkYYS2CiSGt.', NULL, '2017-05-20 23:05:12', '2017-05-20 23:05:12');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `canvas`
--
ALTER TABLE `canvas`
  ADD PRIMARY KEY (`canvasId`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`categoriaId`),
  ADD KEY `categoriaTileset` (`tilesetId`);

--
-- Indices de la tabla `layers`
--
ALTER TABLE `layers`
  ADD PRIMARY KEY (`layerId`),
  ADD KEY `layerTileset` (`tilesetId`),
  ADD KEY `layerMapa` (`mapaId`);

--
-- Indices de la tabla `mapas`
--
ALTER TABLE `mapas`
  ADD PRIMARY KEY (`mapaId`),
  ADD KEY `foraneaUser` (`userId`),
  ADD KEY `foraneaCanvas` (`canvasId`),
  ADD KEY `foraneaTileset` (`tilesetId`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indices de la tabla `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indices de la tabla `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indices de la tabla `tiles`
--
ALTER TABLE `tiles`
  ADD PRIMARY KEY (`tileId`),
  ADD KEY `tileCategoria` (`categoriaId`),
  ADD KEY `tileLayer` (`layerId`);

--
-- Indices de la tabla `tilesets`
--
ALTER TABLE `tilesets`
  ADD PRIMARY KEY (`tilesetId`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `canvas`
--
ALTER TABLE `canvas`
  MODIFY `canvasId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `categoriaId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT de la tabla `layers`
--
ALTER TABLE `layers`
  MODIFY `layerId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `mapas`
--
ALTER TABLE `mapas`
  MODIFY `mapaId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tiles`
--
ALTER TABLE `tiles`
  MODIFY `tileId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=248;
--
-- AUTO_INCREMENT de la tabla `tilesets`
--
ALTER TABLE `tilesets`
  MODIFY `tilesetId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `categoriaTileset` FOREIGN KEY (`tilesetId`) REFERENCES `tilesets` (`tilesetId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `layers`
--
ALTER TABLE `layers`
  ADD CONSTRAINT `layerMapa` FOREIGN KEY (`mapaId`) REFERENCES `mapas` (`mapaId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `layerTileset` FOREIGN KEY (`tilesetId`) REFERENCES `tilesets` (`tilesetId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mapas`
--
ALTER TABLE `mapas`
  ADD CONSTRAINT `foraneaCanvas` FOREIGN KEY (`canvasId`) REFERENCES `canvas` (`canvasId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foraneaTileset` FOREIGN KEY (`tilesetId`) REFERENCES `tilesets` (`tilesetId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foraneaUser` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tiles`
--
ALTER TABLE `tiles`
  ADD CONSTRAINT `tileCategoria` FOREIGN KEY (`categoriaId`) REFERENCES `categorias` (`categoriaId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tileLayer` FOREIGN KEY (`layerId`) REFERENCES `layers` (`layerId`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
