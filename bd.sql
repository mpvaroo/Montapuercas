-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-02-2026 a las 18:58:00
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `automai_gym`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajustes_usuario`
--

CREATE TABLE `ajustes_usuario` (
  `id_ajustes_usuario` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `notificaciones_entrenamiento_activas` tinyint(1) NOT NULL DEFAULT 1,
  `notificaciones_clases_activas` tinyint(1) NOT NULL DEFAULT 1,
  `tono_ia_coach` enum('directo','motivador') NOT NULL DEFAULT 'directo',
  `idioma_preferido` enum('es','en') NOT NULL DEFAULT 'es',
  `semana_empieza_en` enum('lunes','domingo') NOT NULL DEFAULT 'lunes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ajustes_usuario`
--

INSERT INTO `ajustes_usuario` (`id_ajustes_usuario`, `id_usuario`, `notificaciones_entrenamiento_activas`, `notificaciones_clases_activas`, `tono_ia_coach`, `idioma_preferido`, `semana_empieza_en`) VALUES
(1, 3, 1, 1, 'directo', 'es', 'lunes'),
(2, 4, 1, 1, 'directo', 'es', 'lunes'),
(3, 5, 1, 1, 'directo', 'es', 'lunes'),
(6, 8, 1, 1, 'directo', 'es', 'lunes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-45b427b416163d86a662c88d685aaeb6', 'i:1;', 1772024627),
('laravel-cache-45b427b416163d86a662c88d685aaeb6:timer', 'i:1772024627;', 1772024627),
('laravel-cache-6a75b1464724f04a7f7d1f2b0eedca2d', 'i:1;', 1771949655),
('laravel-cache-6a75b1464724f04a7f7d1f2b0eedca2d:timer', 'i:1771949655;', 1771949655),
('laravel-cache-9dad704ea73c73443d47cb074313535c', 'i:1;', 1771975594),
('laravel-cache-9dad704ea73c73443d47cb074313535c:timer', 'i:1771975594;', 1771975594),
('laravel-cache-automai@admin123|127.0.0.1', 'i:1;', 1771949958),
('laravel-cache-automai@admin123|127.0.0.1:timer', 'i:1771949958;', 1771949958),
('laravel-cache-b727aa1631763e305685ff01e5dff72e', 'i:1;', 1771949958),
('laravel-cache-b727aa1631763e305685ff01e5dff72e:timer', 'i:1771949958;', 1771949958),
('laravel-cache-f02a03807cdb0735ad9edbdfc3e478e2', 'i:1;', 1772035918),
('laravel-cache-f02a03807cdb0735ad9edbdfc3e478e2:timer', 'i:1772035918;', 1772035918),
('laravel-cache-manuelmvp20004@gmail.com|127.0.0.1', 'i:1;', 1771975594),
('laravel-cache-manuelmvp20004@gmail.com|127.0.0.1:timer', 'i:1771975594;', 1771975594),
('laravel-cache-manuelmvp2004@gmail.co|127.0.0.1', 'i:1;', 1771949655),
('laravel-cache-manuelmvp2004@gmail.co|127.0.0.1:timer', 'i:1771949655;', 1771949655);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chat_conversaciones`
--

CREATE TABLE `chat_conversaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `resumen` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `chat_conversaciones`
--

INSERT INTO `chat_conversaciones` (`id`, `id_usuario`, `titulo`, `resumen`, `created_at`, `updated_at`) VALUES
(12, 1, 'hola', NULL, '2026-02-24 21:55:10', '2026-02-24 21:55:10'),
(43, 8, 'hola perdona se me habia olvidado pedirtelo antes …', NULL, '2026-02-25 16:56:16', '2026-02-25 16:56:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chat_mensajes_ia`
--

CREATE TABLE `chat_mensajes_ia` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_conversacion` bigint(20) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `rol` enum('user','assistant','tool') NOT NULL,
  `contenido` longtext NOT NULL,
  `tool_name` varchar(255) DEFAULT NULL,
  `tool_payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tool_payload`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `chat_mensajes_ia`
--

INSERT INTO `chat_mensajes_ia` (`id`, `id_conversacion`, `id_usuario`, `rol`, `contenido`, `tool_name`, `tool_payload`, `created_at`) VALUES
(184, 12, 1, 'user', 'hola', NULL, NULL, '2026-02-24 22:55:10'),
(774, 43, 8, 'user', 'hola perdona se me habia olvidado pedirtelo antes me puedes decir mis reservas??', NULL, NULL, '2026-02-25 17:56:16'),
(775, 43, 8, 'tool', '[]', 'my_reservations', '[]', '2026-02-25 17:56:20'),
(776, 43, 8, 'assistant', 'No tienes reservas activas en este momento.', NULL, NULL, '2026-02-25 17:56:22'),
(777, 43, 8, 'user', 'pues quiero hacer una reserva', NULL, NULL, '2026-02-25 17:56:49'),
(778, 43, 8, 'tool', '[{\"id_clase\":10,\"nombre\":\"Dicta incidunt quaerat unde.\",\"instructor\":\"Miss Makenzie Jacobs\",\"dia_semana\":\"jueves\",\"hora\":\"08:16\\u201309:16\",\"fecha_inicio\":\"2026-02-26 08:16:48\",\"cupo_maximo\":14,\"cupo_disponible\":14,\"estado\":\"disponible\"},{\"id_clase\":2,\"nombre\":\"Aut qui ab cupiditate laudantium.\",\"instructor\":\"Clare Kassulke\",\"dia_semana\":\"domingo\",\"hora\":\"08:28\\u201309:28\",\"fecha_inicio\":\"2026-03-01 08:28:23\",\"cupo_maximo\":27,\"cupo_disponible\":27,\"estado\":\"disponible\"},{\"id_clase\":5,\"nombre\":\"Repudiandae eum reiciendis aut reiciendis.\",\"instructor\":\"Christiana Gaylord DVM\",\"dia_semana\":\"lunes\",\"hora\":\"16:46\\u201317:46\",\"fecha_inicio\":\"2026-03-02 16:46:44\",\"cupo_maximo\":27,\"cupo_disponible\":27,\"estado\":\"disponible\"},{\"id_clase\":1,\"nombre\":\"Excepturi voluptas sapiente.\",\"instructor\":\"Franz Rau\",\"dia_semana\":\"martes\",\"hora\":\"00:44\\u201301:44\",\"fecha_inicio\":\"2026-03-03 00:44:17\",\"cupo_maximo\":26,\"cupo_disponible\":26,\"estado\":\"disponible\"},{\"id_clase\":6,\"nombre\":\"Velit ipsum dolore saepe.\",\"instructor\":\"Elinore Hartmann\",\"dia_semana\":\"martes\",\"hora\":\"19:33\\u201320:33\",\"fecha_inicio\":\"2026-03-03 19:33:34\",\"cupo_maximo\":15,\"cupo_disponible\":15,\"estado\":\"disponible\"},{\"id_clase\":23,\"nombre\":\"Spinning Ma\\u00f1ana\",\"instructor\":\"Carlos Ruiz\",\"dia_semana\":\"mi\\u00e9rcoles\",\"hora\":\"09:00\\u201310:00\",\"fecha_inicio\":\"2026-03-04 09:00:00\",\"cupo_maximo\":20,\"cupo_disponible\":20,\"estado\":\"disponible\"},{\"id_clase\":24,\"nombre\":\"Box Cardio\",\"instructor\":\"David Mart\\u00edn\",\"dia_semana\":\"martes\",\"hora\":\"20:00\\u201321:00\",\"fecha_inicio\":\"2026-03-10 20:00:00\",\"cupo_maximo\":18,\"cupo_disponible\":18,\"estado\":\"disponible\"},{\"id_clase\":8,\"nombre\":\"Dolorem quam vel.\",\"instructor\":\"Prof. Isaiah Connelly II\",\"dia_semana\":\"jueves\",\"hora\":\"15:42\\u201316:42\",\"fecha_inicio\":\"2026-03-12 15:42:24\",\"cupo_maximo\":19,\"cupo_disponible\":19,\"estado\":\"disponible\"},{\"id_clase\":9,\"nombre\":\"Officia saepe eaque doloribus qui.\",\"instructor\":\"Dr. Cooper Herzog\",\"dia_semana\":\"s\\u00e1bado\",\"hora\":\"19:01\\u201320:01\",\"fecha_inicio\":\"2026-03-14 19:01:36\",\"cupo_maximo\":30,\"cupo_disponible\":30,\"estado\":\"disponible\"},{\"id_clase\":25,\"nombre\":\"Yoga Nidra\",\"instructor\":\"Marta L\\u00f3pez\",\"dia_semana\":\"martes\",\"hora\":\"19:00\\u201320:00\",\"fecha_inicio\":\"2026-03-17 19:00:00\",\"cupo_maximo\":15,\"cupo_disponible\":15,\"estado\":\"disponible\"},{\"id_clase\":3,\"nombre\":\"Id repellat error sed.\",\"instructor\":\"Lottie Larson\",\"dia_semana\":\"jueves\",\"hora\":\"01:10\\u201302:10\",\"fecha_inicio\":\"2026-03-19 01:10:34\",\"cupo_maximo\":23,\"cupo_disponible\":23,\"estado\":\"disponible\"},{\"id_clase\":7,\"nombre\":\"Eum a delectus natus.\",\"instructor\":\"Sadye Harris\",\"dia_semana\":\"domingo\",\"hora\":\"09:41\\u201310:41\",\"fecha_inicio\":\"2026-03-22 09:41:06\",\"cupo_maximo\":26,\"cupo_disponible\":26,\"estado\":\"disponible\"},{\"id_clase\":4,\"nombre\":\"Molestiae asperiores est.\",\"instructor\":\"Yazmin Brekke\",\"dia_semana\":\"lunes\",\"hora\":\"05:13\\u201306:13\",\"fecha_inicio\":\"2026-03-23 05:13:45\",\"cupo_maximo\":10,\"cupo_disponible\":10,\"estado\":\"disponible\"}]', 'list_classes', '[]', '2026-02-25 17:56:51'),
(779, 43, 8, 'assistant', 'Estas son las clases disponibles:\n\n**Dicta incidunt quaerat unde.** — jueves 26 de febrero de 2026, 08:16–09:16 (instructor: Miss Makenzie Jacobs) — 🟢 14 plazas\n**Aut qui ab cupiditate laudantium.** — domingo 1 de marzo de 2026, 08:28–09:28 (instructor: Clare Kassulke) — 🟢 27 plazas\n**Repudiandae eum reiciendis aut reiciendis.** — lunes 2 de marzo de 2026, 16:46–17:46 (instructor: Christiana Gaylord DVM) — 🟢 27 plazas\n**Excepturi voluptas sapiente.** — martes 3 de marzo de 2026, 00:44–01:44 (instructor: Franz Rau) — 🟢 26 plazas\n**Velit ipsum dolore saepe.** — martes 3 de marzo de 2026, 19:33–20:33 (instructor: Elinore Hartmann) — 🟢 15 plazas\n**Spinning Mañana** — miércoles 4 de marzo de 2026, 09:00–10:00 (instructor: Carlos Ruiz) — 🟢 20 plazas\n**Box Cardio** — martes 10 de marzo de 2026, 20:00–21:00 (instructor: David Martín) — 🟢 18 plazas\n**Dolorem quam vel.** — jueves 12 de marzo de 2026, 15:42–16:42 (instructor: Prof. Isaiah Connelly II) — 🟢 19 plazas\n**Officia saepe eaque doloribus qui.** — sábado 14 de marzo de 2026, 19:01–20:01 (instructor: Dr. Cooper Herzog) — 🟢 30 plazas\n**Yoga Nidra** — martes 17 de marzo de 2026, 19:00–20:00 (instructor: Marta López) — 🟢 15 plazas\n**Id repellat error sed.** — jueves 19 de marzo de 2026, 01:10–02:10 (instructor: Lottie Larson) — 🟢 23 plazas\n**Eum a delectus natus.** — domingo 22 de marzo de 2026, 09:41–10:41 (instructor: Sadye Harris) — 🟢 26 plazas\n**Molestiae asperiores est.** — lunes 23 de marzo de 2026, 05:13–06:13 (instructor: Yazmin Brekke) — 🟢 10 plazas\n\n¿Quieres reservar alguna? Dime el nombre de la clase.', NULL, NULL, '2026-02-25 17:57:02'),
(780, 43, 8, 'user', 'quiero reservar Box Cardio', NULL, NULL, '2026-02-25 17:57:15'),
(781, 43, 8, 'tool', '{\"exito\":false,\"pasada\":true,\"error\":\"La clase \\\"Spinning\\\" ya ha tenido lugar. No es posible reservar clases pasadas.\"}', 'book_class', '{\"id_clase\":11}', '2026-02-25 17:57:19'),
(782, 43, 8, 'assistant', '❌ No es posible reservar esa clase porque ya ha tenido lugar. Solo puedes reservar clases futuras. ¿Quieres que te muestre las clases disponibles?', NULL, NULL, '2026-02-25 17:57:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases_gimnasio`
--

CREATE TABLE `clases_gimnasio` (
  `id_clase_gimnasio` int(10) UNSIGNED NOT NULL,
  `id_tipo_clase` int(10) UNSIGNED NOT NULL,
  `titulo_clase` varchar(100) NOT NULL,
  `descripcion_clase` varchar(220) DEFAULT NULL,
  `instructor_clase` varchar(80) DEFAULT NULL,
  `fecha_inicio_clase` datetime NOT NULL,
  `fecha_fin_clase` datetime NOT NULL,
  `cupo_maximo_clase` smallint(5) UNSIGNED NOT NULL DEFAULT 20,
  `estado_clase` enum('borrador','publicada','cancelada') NOT NULL DEFAULT 'publicada',
  `fecha_creacion_clase` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clases_gimnasio`
--

INSERT INTO `clases_gimnasio` (`id_clase_gimnasio`, `id_tipo_clase`, `titulo_clase`, `descripcion_clase`, `instructor_clase`, `fecha_inicio_clase`, `fecha_fin_clase`, `cupo_maximo_clase`, `estado_clase`, `fecha_creacion_clase`) VALUES
(1, 3, 'Excepturi voluptas sapiente.', 'Rerum omnis eos quod inventore aliquam. Animi saepe sapiente molestiae dolorem vero aliquam culpa. Provident tempora adipisci itaque non ea. Vero neque provident qui quia.', 'Franz Rau', '2026-03-03 00:44:17', '2026-03-03 01:44:17', 26, 'publicada', '2026-02-24 13:03:46'),
(2, 1, 'Aut qui ab cupiditate laudantium.', 'Debitis at molestiae repellat consequuntur quam. Facilis corporis aut nobis mollitia. Qui saepe tempora non molestiae ipsa animi aspernatur.', 'Clare Kassulke', '2026-03-01 08:28:23', '2026-03-01 09:28:23', 27, 'publicada', '2026-02-24 13:03:46'),
(3, 2, 'Id repellat error sed.', 'Consequatur libero voluptatem voluptatem aut. Impedit magni voluptatem consequatur. Voluptas voluptatum amet molestias.', 'Lottie Larson', '2026-03-19 01:10:34', '2026-03-19 02:10:34', 23, 'publicada', '2026-02-24 13:03:46'),
(4, 2, 'Molestiae asperiores est.', 'Explicabo quia et nesciunt dicta modi debitis aliquid. Voluptate molestiae ut ex ullam et voluptatem non culpa. At laborum corporis quia eos.', 'Yazmin Brekke', '2026-03-23 05:13:45', '2026-03-23 06:13:45', 10, 'publicada', '2026-02-24 13:03:46'),
(5, 3, 'Repudiandae eum reiciendis aut reiciendis.', 'Labore maiores sed molestias animi quasi praesentium libero. Officiis sint sint quis quis et animi. Et dolores et esse culpa repellendus ut totam. Velit nesciunt unde esse officia temporibus facere.', 'Christiana Gaylord DVM', '2026-03-02 16:46:44', '2026-03-02 17:46:44', 27, 'publicada', '2026-02-24 13:03:46'),
(6, 2, 'Velit ipsum dolore saepe.', 'Recusandae nostrum eveniet commodi quis. Omnis minima incidunt et aperiam ipsam.', 'Elinore Hartmann', '2026-03-03 19:33:34', '2026-03-03 20:33:34', 15, 'publicada', '2026-02-24 13:03:46'),
(7, 3, 'Eum a delectus natus.', 'Incidunt cum eligendi eveniet repellendus eaque perspiciatis. Dolores est cum sunt. Nihil fugiat ducimus aliquid praesentium laborum ad. Sunt porro placeat ipsum aperiam.', 'Sadye Harris', '2026-03-22 09:41:06', '2026-03-22 10:41:06', 26, 'publicada', '2026-02-24 13:03:46'),
(8, 4, 'Dolorem quam vel.', 'Nobis placeat delectus aliquam omnis earum. Fuga unde aliquid nihil exercitationem quos quas porro. Quo illum ea ex dolores dignissimos unde rerum.', 'Prof. Isaiah Connelly II', '2026-03-12 15:42:24', '2026-03-12 16:42:24', 19, 'publicada', '2026-02-24 13:03:46'),
(9, 1, 'Officia saepe eaque doloribus qui.', 'Delectus odit quibusdam et voluptate rerum consequatur maiores aut. Velit recusandae dignissimos eos deserunt iste maiores et. Error reiciendis repellat enim ducimus temporibus exercitationem.', 'Dr. Cooper Herzog', '2026-03-14 19:01:36', '2026-03-14 20:01:36', 30, 'publicada', '2026-02-24 13:03:46'),
(10, 1, 'Dicta incidunt quaerat unde.', 'Quae harum doloribus ut qui repudiandae. Delectus recusandae porro impedit inventore. Tempora quos quis iusto alias quo natus cumque. Sed impedit doloribus quod quod eius veritatis velit.', 'Miss Makenzie Jacobs', '2026-02-26 08:16:48', '2026-02-26 09:16:48', 14, 'publicada', '2026-02-24 13:03:46'),
(11, 4, 'Spinning', NULL, 'Manuel', '2025-02-10 12:15:00', '2025-02-10 13:15:00', 20, 'publicada', '2026-02-24 20:20:21'),
(12, 2, 'WaterPolo', NULL, 'Mario', '2026-02-25 14:00:00', '2026-02-25 15:00:00', 20, 'publicada', '2026-02-24 23:30:00'),
(13, 1, 'Spinning Mañana', 'Sesión intensa de ciclismo indoor', 'Carlos Ruiz', '2026-01-07 09:00:00', '2026-01-07 10:00:00', 20, 'publicada', '2026-02-25 14:12:56'),
(14, 1, 'Yoga Restaurativo', 'Estiramiento profundo y relajación', 'Marta López', '2026-01-12 18:30:00', '2026-01-12 19:30:00', 15, 'publicada', '2026-02-25 14:12:56'),
(15, 1, 'Zumba Fitness', 'Baile y cardio latino', 'Ana Blanco', '2026-01-15 19:00:00', '2026-01-15 20:00:00', 25, 'publicada', '2026-02-25 14:12:56'),
(16, 1, 'Pilates Intermedio', 'Core y postura', 'Marta López', '2026-01-21 10:00:00', '2026-01-21 11:00:00', 12, 'publicada', '2026-02-25 14:12:56'),
(17, 1, 'Box Cardio', 'Golpeo técnico y resistencia', 'David Martín', '2026-01-28 20:00:00', '2026-01-28 21:00:00', 18, 'publicada', '2026-02-25 14:12:56'),
(18, 1, 'Spinning Tarde', 'Intervalos de alta intensidad', 'Carlos Ruiz', '2026-02-03 19:30:00', '2026-02-03 20:30:00', 20, 'publicada', '2026-02-25 14:12:56'),
(19, 1, 'Yoga Vinyasa', 'Flujo dinámico y equilibrio', 'Marta López', '2026-02-10 09:00:00', '2026-02-10 10:00:00', 15, 'publicada', '2026-02-25 14:12:56'),
(20, 1, 'Zumba Fitness', 'Baile y cardio latino', 'Ana Blanco', '2026-02-14 18:00:00', '2026-02-14 19:00:00', 25, 'publicada', '2026-02-25 14:12:56'),
(21, 1, 'TRX Total Body', 'Entrenamiento en suspensión', 'David Martín', '2026-02-18 20:00:00', '2026-02-18 21:00:00', 14, 'publicada', '2026-02-25 14:12:56'),
(22, 1, 'Pilates Avanzado', 'Core profundo y control', 'Marta López', '2026-02-24 10:30:00', '2026-02-24 11:30:00', 12, 'publicada', '2026-02-25 14:12:56'),
(23, 1, 'Spinning Mañana', 'Sesión de resistencia aeróbica', 'Carlos Ruiz', '2026-03-04 09:00:00', '2026-03-04 10:00:00', 20, 'publicada', '2026-02-25 14:12:56'),
(24, 1, 'Box Cardio', 'Técnica y potencia', 'David Martín', '2026-03-10 20:00:00', '2026-03-10 21:00:00', 18, 'publicada', '2026-02-25 14:12:56'),
(25, 1, 'Yoga Nidra', 'Relajación profunda guiada', 'Marta López', '2026-03-17 19:00:00', '2026-03-17 20:00:00', 15, 'publicada', '2026-02-25 14:12:56'),
(26, 1, 'Zumba Fitness', 'Baile y cardio latino', 'Ana Blanco', '2026-03-21 18:30:00', '2026-03-21 19:30:00', 25, '', '2026-02-25 14:12:56'),
(27, 1, 'TRX Total Body', 'Funcional de cuerpo completo', 'David Martín', '2026-03-28 10:00:00', '2026-03-28 11:00:00', 14, '', '2026-02-25 14:12:56'),
(28, 1, 'Spinning Tarde', 'Sprints y recuperación activa', 'Carlos Ruiz', '2026-04-07 19:30:00', '2026-04-07 20:30:00', 20, '', '2026-02-25 14:12:56'),
(29, 1, 'Pilates Intermedio', 'Movilidad y fuerza de core', 'Marta López', '2026-04-14 10:00:00', '2026-04-14 11:00:00', 12, '', '2026-02-25 14:12:56'),
(30, 1, 'Box Cardio', 'Combos y cardio explosivo', 'David Martín', '2026-04-22 20:00:00', '2026-04-22 21:00:00', 18, '', '2026-02-25 14:12:56'),
(31, 1, 'Yoga Restaurativo', 'Descanso activo y flexibilidad', 'Marta López', '2026-04-29 18:00:00', '2026-04-29 19:00:00', 15, '', '2026-02-25 14:12:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conversaciones_ia`
--

CREATE TABLE `conversaciones_ia` (
  `id_conversacion_ia` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `titulo_conversacion` varchar(120) DEFAULT NULL,
  `fecha_creacion_conversacion` datetime NOT NULL DEFAULT current_timestamp(),
  `conversacion_activa` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicios`
--

CREATE TABLE `ejercicios` (
  `id_ejercicio` int(10) UNSIGNED NOT NULL,
  `nombre_ejercicio` varchar(120) NOT NULL,
  `grupo_muscular_principal` enum('pecho','espalda','pierna','hombro','biceps','triceps','core','cardio','fullbody') NOT NULL,
  `descripcion_ejercicio` varchar(220) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ejercicios`
--

INSERT INTO `ejercicios` (`id_ejercicio`, `nombre_ejercicio`, `grupo_muscular_principal`, `descripcion_ejercicio`) VALUES
(1, 'Press banca', 'pecho', 'Press en banco con barra'),
(2, 'Dominadas', 'espalda', 'Tracción vertical con peso corporal'),
(3, 'Sentadilla', 'pierna', 'Sentadilla con barra'),
(4, 'Curl bíceps', 'biceps', 'Curl con mancuernas o barra'),
(5, 'Plancha', 'core', 'Isométrico de core'),
(6, 'Remo con barra', 'espalda', 'Creado por IA Coach'),
(7, 'Peso muerto', 'pierna', 'Creado por IA Coach'),
(8, 'Flyes con bandas elásticas', 'pecho', 'Creado por IA Coach'),
(9, 'Pulley de pecho (cable)', 'pecho', 'Creado por IA Coach'),
(10, 'Prensa de hombros con mancuernas', 'hombro', 'Creado por IA Coach'),
(11, 'Pullover con cuerda', 'espalda', 'Creado por IA Coach'),
(12, 'Flexiones', 'pecho', 'Creado por IA Coach');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_ia`
--

CREATE TABLE `mensajes_ia` (
  `id_mensaje_ia` int(10) UNSIGNED NOT NULL,
  `id_conversacion_ia` int(10) UNSIGNED NOT NULL,
  `rol_mensaje` enum('usuario','asistente','sistema') NOT NULL,
  `contenido_mensaje` text NOT NULL,
  `acciones_mensaje` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`acciones_mensaje`)),
  `fecha_mensaje` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_02_11_150000_create_custom_gym_schema', 1),
(4, '2026_02_17_180000_update_origen_reserva_enum', 1),
(5, '2026_02_17_182000_drop_checkin_reserva_table', 1),
(6, '2026_02_19_100000_add_dia_semana_to_rutinas_usuario_table', 1),
(7, '2026_02_23_000000_add_email_verified_at_to_usuarios_table', 1),
(8, '2026_02_24_082125_create_personal_access_tokens_table', 1),
(11, '2026_02_24_153449_create_password_reset_tokens_table', 2),
(12, '2026_02_24_154107_modify_password_reset_tokens_table_for_correo_usuario', 2),
(13, '2026_02_24_174545_create_chat_conversaciones_table', 2),
(14, '2026_02_24_174616_create_chat_mensajes_ia_table', 2),
(15, '2026_02_25_115251_add_foreign_keys_to_chat_tables', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `correo_usuario` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`correo_usuario`, `token`, `created_at`) VALUES
('manuelmvp2004@gmail.com', '$2y$12$P5EDwO2KcZtomDwP1sPRU.XvjfhEf87tU0G8AWCvXA1Rgs36kV70W', '2026-02-25 12:18:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles_usuario`
--

CREATE TABLE `perfiles_usuario` (
  `id_perfil_usuario` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `nombre_real_usuario` varchar(100) DEFAULT NULL,
  `telefono_usuario` varchar(30) DEFAULT NULL,
  `ruta_foto_perfil_usuario` varchar(180) DEFAULT NULL,
  `objetivo_principal_usuario` enum('definir','volumen','rendimiento','salud') NOT NULL DEFAULT 'salud',
  `dias_entrenamiento_semana_usuario` tinyint(3) UNSIGNED NOT NULL DEFAULT 3,
  `nivel_usuario` enum('principiante','intermedio','avanzado') NOT NULL DEFAULT 'principiante',
  `peso_kg_usuario` decimal(5,2) DEFAULT NULL,
  `altura_cm_usuario` smallint(5) UNSIGNED DEFAULT NULL,
  `fecha_inicio_usuario` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `perfiles_usuario`
--

INSERT INTO `perfiles_usuario` (`id_perfil_usuario`, `id_usuario`, `nombre_real_usuario`, `telefono_usuario`, `ruta_foto_perfil_usuario`, `objetivo_principal_usuario`, `dias_entrenamiento_semana_usuario`, `nivel_usuario`, `peso_kg_usuario`, `altura_cm_usuario`, `fecha_inicio_usuario`) VALUES
(1, 3, 'Rebeka Wuckert', '+1 (678) 546-1565', NULL, 'volumen', 2, 'intermedio', 99.99, 195, '1994-09-07'),
(2, 4, 'Dr. Travis Denesik', '341.867.7104', NULL, 'volumen', 2, 'intermedio', 52.85, 203, '1999-03-10'),
(3, 5, 'Prof. Cory Jakubowski', '386-466-2068', NULL, 'rendimiento', 6, 'intermedio', 119.45, 202, '2011-12-21'),
(6, 8, 'Manuel', '633633633', NULL, 'salud', 3, 'principiante', 85.00, 185, '2026-02-24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_progreso`
--

CREATE TABLE `registros_progreso` (
  `id_registro_progreso` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `fecha_registro` date NOT NULL,
  `peso_kg_registro` decimal(5,2) DEFAULT NULL,
  `cintura_cm_registro` decimal(5,2) DEFAULT NULL,
  `pecho_cm_registro` decimal(5,2) DEFAULT NULL,
  `cadera_cm_registro` decimal(5,2) DEFAULT NULL,
  `notas_progreso` varchar(220) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `registros_progreso`
--

INSERT INTO `registros_progreso` (`id_registro_progreso`, `id_usuario`, `fecha_registro`, `peso_kg_registro`, `cintura_cm_registro`, `pecho_cm_registro`, `cadera_cm_registro`, `notas_progreso`) VALUES
(33, 8, '2025-12-16', 94.50, 91.00, 106.00, 104.50, 'Inicio del plan. Motivación alta.'),
(34, 8, '2025-12-30', 93.10, 90.20, 105.50, 103.80, 'Primera semana bien. Sin dulces navideños.'),
(35, 8, '2026-01-13', 91.60, 89.40, 104.80, 103.00, 'Vuelta al ritmo tras Reyes. Cintura bajando.'),
(36, 8, '2026-01-27', 90.00, 88.50, 104.00, 102.20, 'Gran semana. Duermo mejor y con más energía.'),
(37, 8, '2026-02-10', 88.30, 87.60, 103.20, 101.40, 'Pantalones más holgados. Visible en el espejo.'),
(38, 8, '2026-02-24', 86.80, 86.80, 102.50, 100.60, 'Objetivo del mes cumplido. -7.7 kg en total.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas_clase`
--

CREATE TABLE `reservas_clase` (
  `id_reserva_clase` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `id_clase_gimnasio` int(10) UNSIGNED NOT NULL,
  `estado_reserva` enum('reservada','cancelada','asistio','no_asistio') NOT NULL DEFAULT 'reservada',
  `fecha_reserva` datetime NOT NULL DEFAULT current_timestamp(),
  `origen_reserva` enum('usuario','ia_coach','admin','recepcion') DEFAULT 'usuario',
  `notas_reserva` varchar(180) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(10) UNSIGNED NOT NULL,
  `nombre_rol` varchar(30) NOT NULL,
  `descripcion_rol` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion_rol`) VALUES
(1, 'usuario', 'Acceso a zona usuario'),
(2, 'admin', 'Acceso a panel admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutinas_ejercicios`
--

CREATE TABLE `rutinas_ejercicios` (
  `id_rutina_ejercicio` int(10) UNSIGNED NOT NULL,
  `id_rutina_usuario` int(10) UNSIGNED NOT NULL,
  `id_ejercicio` int(10) UNSIGNED NOT NULL,
  `orden_en_rutina` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `series_objetivo` smallint(5) UNSIGNED NOT NULL DEFAULT 3,
  `repeticiones_objetivo` varchar(30) NOT NULL DEFAULT '8-12',
  `peso_objetivo_kg` decimal(6,2) DEFAULT NULL,
  `rpe_objetivo` decimal(3,1) DEFAULT NULL,
  `descanso_segundos` smallint(5) UNSIGNED DEFAULT NULL,
  `notas_ejercicio` varchar(220) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rutinas_ejercicios`
--

INSERT INTO `rutinas_ejercicios` (`id_rutina_ejercicio`, `id_rutina_usuario`, `id_ejercicio`, `orden_en_rutina`, `series_objetivo`, `repeticiones_objetivo`, `peso_objetivo_kg`, `rpe_objetivo`, `descanso_segundos`, `notas_ejercicio`) VALUES
(67, 25, 6, 1, 4, '6-8', NULL, NULL, NULL, 'Enfócate en la forma: mantén la espalda recta, baja la barra hasta las rodillas'),
(68, 25, 6, 2, 3, '8-10', NULL, NULL, NULL, 'Usa agarre pronado. Enfócate en contraer los dorsales al levantar'),
(69, 25, 10, 3, 4, '8-10', NULL, NULL, NULL, 'Mantén el torso inclinado a 45°. Controla el descenso para maximizar estiramiento'),
(70, 25, 5, 4, 3, '10-12', NULL, NULL, NULL, 'Mantén el agarre ancho. Enfócate en contraer los dorsales al final de la contracción');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutinas_usuario`
--

CREATE TABLE `rutinas_usuario` (
  `id_rutina_usuario` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `nombre_rutina_usuario` varchar(140) NOT NULL,
  `objetivo_rutina_usuario` enum('definir','volumen','rendimiento','salud') NOT NULL DEFAULT 'salud',
  `nivel_rutina_usuario` enum('principiante','intermedio','avanzado') NOT NULL DEFAULT 'principiante',
  `duracion_estimada_minutos` smallint(5) UNSIGNED DEFAULT NULL,
  `origen_rutina` enum('usuario','ia_coach','plantilla') NOT NULL DEFAULT 'usuario',
  `instrucciones_rutina` text DEFAULT NULL,
  `fecha_creacion_rutina` datetime NOT NULL DEFAULT current_timestamp(),
  `rutina_activa` tinyint(1) NOT NULL DEFAULT 1,
  `dia_semana` enum('lunes','martes','miercoles','jueves','viernes','sabado','domingo','descanso') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rutinas_usuario`
--

INSERT INTO `rutinas_usuario` (`id_rutina_usuario`, `id_usuario`, `nombre_rutina_usuario`, `objetivo_rutina_usuario`, `nivel_rutina_usuario`, `duracion_estimada_minutos`, `origen_rutina`, `instrucciones_rutina`, `fecha_creacion_rutina`, `rutina_activa`, `dia_semana`) VALUES
(25, 8, 'rutina espalda con ia coach', 'volumen', 'avanzado', 120, 'ia_coach', 'siempre al fallo. Prioriza técnica correcta para evitar lesiones. Usa peso adecuado para tu nivel.', '2026-02-25 17:45:04', 1, 'lunes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguridad_usuario`
--

CREATE TABLE `seguridad_usuario` (
  `id_seguridad_usuario` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `requiere_cambio_contrasena` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_ultimo_cambio_contrasena` datetime DEFAULT NULL,
  `intentos_fallidos_login` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `fecha_ultimo_intento_fallido` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `seguridad_usuario`
--

INSERT INTO `seguridad_usuario` (`id_seguridad_usuario`, `id_usuario`, `requiere_cambio_contrasena`, `fecha_ultimo_cambio_contrasena`, `intentos_fallidos_login`, `fecha_ultimo_intento_fallido`) VALUES
(1, 3, 0, NULL, 0, NULL),
(2, 4, 0, NULL, 0, NULL),
(3, 5, 0, NULL, 0, NULL),
(6, 8, 0, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Gxzts7P26Irdeb5YzV8z89kpImP7c7UvxOVSRydV', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRGwyOUVoeGxKdXE2eTlrMUk3aFFnaE9qNjhEdlYyaTJzSnBCR0VoQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvaWEvY29udmVyc2F0aW9ucy80My9tZXNzYWdlcz9saW1pdD0yMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6ODt9', 1772042254),
('urgtUtiz2mKhmOp6QF91OpehFGpwSIkluAZ5XXz0', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRXdodk5UNzZsbExKTmRrazJxVjg1blg5amFQSk12VnRld3Fyd2ZqRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvaWEvY29udmVyc2F0aW9ucyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6ODt9', 1772035730);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_clase`
--

CREATE TABLE `tipos_clase` (
  `id_tipo_clase` int(10) UNSIGNED NOT NULL,
  `nombre_tipo_clase` varchar(60) NOT NULL,
  `descripcion_tipo_clase` varchar(180) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipos_clase`
--

INSERT INTO `tipos_clase` (`id_tipo_clase`, `nombre_tipo_clase`, `descripcion_tipo_clase`) VALUES
(1, 'Yoga Flow', 'Movilidad y respiración'),
(2, 'HIIT Express', 'Alta intensidad 30-45 min'),
(3, 'Fuerza', 'Fuerza guiada'),
(4, 'Spinning', 'Ciclismo indoor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `correo_usuario` varchar(120) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `hash_contrasena_usuario` varchar(255) NOT NULL,
  `nombre_mostrado_usuario` varchar(80) NOT NULL,
  `estado_usuario` enum('activo','bloqueado','pendiente') NOT NULL DEFAULT 'activo',
  `fecha_creacion_usuario` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_ultimo_acceso_usuario` datetime DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `correo_usuario`, `email_verified_at`, `hash_contrasena_usuario`, `nombre_mostrado_usuario`, `estado_usuario`, `fecha_creacion_usuario`, `fecha_ultimo_acceso_usuario`, `remember_token`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`) VALUES
(1, 'admin@automai.com', '2026-02-24 13:08:27', '$2y$12$KaTdJnnjDyd8oycCLZgDR.G5WoEVOBQ83EmbnEwtSeDj5TBwntMdW', 'Administrador', 'activo', '2026-02-24 13:03:45', NULL, NULL, NULL, NULL, NULL),
(2, 'mario@gmail.com', NULL, '$2y$12$A9sv4twnjivyAiD/ba8Qj.KWOI7LaqOiGL2xad9PYhnv9hbNEi2Xm', 'Mario User', 'activo', '2026-02-24 13:03:45', NULL, NULL, NULL, NULL, NULL),
(3, 'lukas.lowe@example.org', NULL, '$2y$12$adEi6hqZDowm.ZV5ebuPGuyZYfbHLpimHBxrnxY7krFQZKo6xU1rO', 'Talon Mueller', 'activo', '2026-02-24 13:03:46', NULL, 'Vp4nLWPC83', NULL, NULL, NULL),
(4, 'jhill@example.org', NULL, '$2y$12$adEi6hqZDowm.ZV5ebuPGuyZYfbHLpimHBxrnxY7krFQZKo6xU1rO', 'Ansley Metz', 'activo', '2026-02-24 13:03:46', NULL, '42oxSHGtS3', NULL, NULL, NULL),
(5, 'ceasar.keeling@example.net', NULL, '$2y$12$adEi6hqZDowm.ZV5ebuPGuyZYfbHLpimHBxrnxY7krFQZKo6xU1rO', 'Gladys Hessel', 'activo', '2026-02-24 13:03:46', NULL, 'iD5UKF7neZ', NULL, NULL, NULL),
(8, 'manuelmvp2004@gmail.com', '2026-02-24 14:26:10', '$2y$12$.y9/TcluraCLdbBUQWXAQuJwQzXVBXi0vRK6LHWP9gJtoAEFoG.Ky', 'mpvaroo', 'activo', '2026-02-24 16:25:46', NULL, 'eVtPtDJAgA8Mo5W3JGv19ubaLEcRAVtN5qmrF5MMutNIxWyg8Le5pcMw8nyV', NULL, NULL, NULL),
(9, 'marioromeiro@gmail.com', NULL, '$2y$12$w2LVctLwXLa6pvJ3qytHou/93qIxZh2ZocGIDJ4LrhYeddtt.MmIS', 'mario', 'pendiente', '2026-02-24 23:34:14', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_roles`
--

CREATE TABLE `usuarios_roles` (
  `id_usuario_rol` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `id_rol` int(10) UNSIGNED NOT NULL,
  `fecha_asignacion_rol` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios_roles`
--

INSERT INTO `usuarios_roles` (`id_usuario_rol`, `id_usuario`, `id_rol`, `fecha_asignacion_rol`) VALUES
(1, 1, 2, '2026-02-24 14:03:45'),
(2, 2, 1, '2026-02-24 14:03:45'),
(3, 3, 1, '2026-02-24 14:03:46'),
(4, 4, 1, '2026-02-24 14:03:46'),
(5, 5, 1, '2026-02-24 14:03:46'),
(6, 9, 2, '2026-02-24 23:34:14');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ajustes_usuario`
--
ALTER TABLE `ajustes_usuario`
  ADD PRIMARY KEY (`id_ajustes_usuario`),
  ADD UNIQUE KEY `ajustes_usuario_id_usuario_unique` (`id_usuario`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indices de la tabla `chat_conversaciones`
--
ALTER TABLE `chat_conversaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_conversaciones_id_usuario_index` (`id_usuario`);

--
-- Indices de la tabla `chat_mensajes_ia`
--
ALTER TABLE `chat_mensajes_ia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_mensajes_ia_id_conversacion_id_index` (`id_conversacion`,`id`),
  ADD KEY `chat_mensajes_ia_id_usuario_index` (`id_usuario`);

--
-- Indices de la tabla `clases_gimnasio`
--
ALTER TABLE `clases_gimnasio`
  ADD PRIMARY KEY (`id_clase_gimnasio`),
  ADD KEY `clases_gimnasio_id_tipo_clase_foreign` (`id_tipo_clase`);

--
-- Indices de la tabla `conversaciones_ia`
--
ALTER TABLE `conversaciones_ia`
  ADD PRIMARY KEY (`id_conversacion_ia`),
  ADD KEY `conversaciones_ia_id_usuario_foreign` (`id_usuario`);

--
-- Indices de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  ADD PRIMARY KEY (`id_ejercicio`),
  ADD UNIQUE KEY `ejercicios_nombre_ejercicio_unique` (`nombre_ejercicio`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mensajes_ia`
--
ALTER TABLE `mensajes_ia`
  ADD PRIMARY KEY (`id_mensaje_ia`),
  ADD KEY `mensajes_ia_id_conversacion_ia_foreign` (`id_conversacion_ia`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`correo_usuario`);

--
-- Indices de la tabla `perfiles_usuario`
--
ALTER TABLE `perfiles_usuario`
  ADD PRIMARY KEY (`id_perfil_usuario`),
  ADD UNIQUE KEY `perfiles_usuario_id_usuario_unique` (`id_usuario`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indices de la tabla `registros_progreso`
--
ALTER TABLE `registros_progreso`
  ADD PRIMARY KEY (`id_registro_progreso`),
  ADD UNIQUE KEY `uq_usuario_fecha` (`id_usuario`,`fecha_registro`);

--
-- Indices de la tabla `reservas_clase`
--
ALTER TABLE `reservas_clase`
  ADD PRIMARY KEY (`id_reserva_clase`),
  ADD UNIQUE KEY `uq_usuario_clase` (`id_usuario`,`id_clase_gimnasio`),
  ADD KEY `reservas_clase_id_clase_gimnasio_foreign` (`id_clase_gimnasio`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `roles_nombre_rol_unique` (`nombre_rol`);

--
-- Indices de la tabla `rutinas_ejercicios`
--
ALTER TABLE `rutinas_ejercicios`
  ADD PRIMARY KEY (`id_rutina_ejercicio`),
  ADD KEY `rutinas_ejercicios_id_rutina_usuario_foreign` (`id_rutina_usuario`),
  ADD KEY `rutinas_ejercicios_id_ejercicio_foreign` (`id_ejercicio`);

--
-- Indices de la tabla `rutinas_usuario`
--
ALTER TABLE `rutinas_usuario`
  ADD PRIMARY KEY (`id_rutina_usuario`),
  ADD KEY `rutinas_usuario_id_usuario_foreign` (`id_usuario`);

--
-- Indices de la tabla `seguridad_usuario`
--
ALTER TABLE `seguridad_usuario`
  ADD PRIMARY KEY (`id_seguridad_usuario`),
  ADD UNIQUE KEY `seguridad_usuario_id_usuario_unique` (`id_usuario`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `tipos_clase`
--
ALTER TABLE `tipos_clase`
  ADD PRIMARY KEY (`id_tipo_clase`),
  ADD UNIQUE KEY `tipos_clase_nombre_tipo_clase_unique` (`nombre_tipo_clase`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuarios_correo_usuario_unique` (`correo_usuario`);

--
-- Indices de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD PRIMARY KEY (`id_usuario_rol`),
  ADD UNIQUE KEY `uq_usuario_rol` (`id_usuario`,`id_rol`),
  ADD KEY `usuarios_roles_id_rol_foreign` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ajustes_usuario`
--
ALTER TABLE `ajustes_usuario`
  MODIFY `id_ajustes_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `chat_conversaciones`
--
ALTER TABLE `chat_conversaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `chat_mensajes_ia`
--
ALTER TABLE `chat_mensajes_ia`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=783;

--
-- AUTO_INCREMENT de la tabla `clases_gimnasio`
--
ALTER TABLE `clases_gimnasio`
  MODIFY `id_clase_gimnasio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `conversaciones_ia`
--
ALTER TABLE `conversaciones_ia`
  MODIFY `id_conversacion_ia` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  MODIFY `id_ejercicio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensajes_ia`
--
ALTER TABLE `mensajes_ia`
  MODIFY `id_mensaje_ia` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `perfiles_usuario`
--
ALTER TABLE `perfiles_usuario`
  MODIFY `id_perfil_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registros_progreso`
--
ALTER TABLE `registros_progreso`
  MODIFY `id_registro_progreso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `reservas_clase`
--
ALTER TABLE `reservas_clase`
  MODIFY `id_reserva_clase` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `rutinas_ejercicios`
--
ALTER TABLE `rutinas_ejercicios`
  MODIFY `id_rutina_ejercicio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de la tabla `rutinas_usuario`
--
ALTER TABLE `rutinas_usuario`
  MODIFY `id_rutina_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `seguridad_usuario`
--
ALTER TABLE `seguridad_usuario`
  MODIFY `id_seguridad_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tipos_clase`
--
ALTER TABLE `tipos_clase`
  MODIFY `id_tipo_clase` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  MODIFY `id_usuario_rol` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ajustes_usuario`
--
ALTER TABLE `ajustes_usuario`
  ADD CONSTRAINT `ajustes_usuario_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `chat_conversaciones`
--
ALTER TABLE `chat_conversaciones`
  ADD CONSTRAINT `chat_conversaciones_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `chat_mensajes_ia`
--
ALTER TABLE `chat_mensajes_ia`
  ADD CONSTRAINT `chat_mensajes_ia_id_conversacion_foreign` FOREIGN KEY (`id_conversacion`) REFERENCES `chat_conversaciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_mensajes_ia_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `clases_gimnasio`
--
ALTER TABLE `clases_gimnasio`
  ADD CONSTRAINT `clases_gimnasio_id_tipo_clase_foreign` FOREIGN KEY (`id_tipo_clase`) REFERENCES `tipos_clase` (`id_tipo_clase`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `conversaciones_ia`
--
ALTER TABLE `conversaciones_ia`
  ADD CONSTRAINT `conversaciones_ia_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mensajes_ia`
--
ALTER TABLE `mensajes_ia`
  ADD CONSTRAINT `mensajes_ia_id_conversacion_ia_foreign` FOREIGN KEY (`id_conversacion_ia`) REFERENCES `conversaciones_ia` (`id_conversacion_ia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `perfiles_usuario`
--
ALTER TABLE `perfiles_usuario`
  ADD CONSTRAINT `perfiles_usuario_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `registros_progreso`
--
ALTER TABLE `registros_progreso`
  ADD CONSTRAINT `registros_progreso_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservas_clase`
--
ALTER TABLE `reservas_clase`
  ADD CONSTRAINT `reservas_clase_id_clase_gimnasio_foreign` FOREIGN KEY (`id_clase_gimnasio`) REFERENCES `clases_gimnasio` (`id_clase_gimnasio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservas_clase_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rutinas_ejercicios`
--
ALTER TABLE `rutinas_ejercicios`
  ADD CONSTRAINT `rutinas_ejercicios_id_ejercicio_foreign` FOREIGN KEY (`id_ejercicio`) REFERENCES `ejercicios` (`id_ejercicio`) ON UPDATE CASCADE,
  ADD CONSTRAINT `rutinas_ejercicios_id_rutina_usuario_foreign` FOREIGN KEY (`id_rutina_usuario`) REFERENCES `rutinas_usuario` (`id_rutina_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rutinas_usuario`
--
ALTER TABLE `rutinas_usuario`
  ADD CONSTRAINT `rutinas_usuario_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `seguridad_usuario`
--
ALTER TABLE `seguridad_usuario`
  ADD CONSTRAINT `seguridad_usuario_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD CONSTRAINT `usuarios_roles_id_rol_foreign` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_roles_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
