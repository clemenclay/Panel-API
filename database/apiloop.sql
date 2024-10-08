-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 08, 2024 at 02:58 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `apiloop`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(4, '2024_10_07_223910_create_postman_collections_table', 1),
(5, '2024_10_07_223921_create_request_logs_table', 1),
(6, '2024_10_08_010240_create_permission_tables', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `postman_collections`
--

CREATE TABLE `postman_collections` (
  `id` int NOT NULL,
  `postman_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `schema_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `postman_collections`
--

INSERT INTO `postman_collections` (`id`, `postman_id`, `name`, `schema_url`) VALUES
(1, 'b60bf1fa-6610-4b3e-a055-5b8928027f33', 'SGI', 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json');

-- --------------------------------------------------------

--
-- Table structure for table `postman_requests`
--

CREATE TABLE `postman_requests` (
  `id` int NOT NULL,
  `collection_id` int DEFAULT NULL,
  `request_name` varchar(255) DEFAULT NULL,
  `method` varchar(255) DEFAULT NULL,
  `url` text,
  `headers` text,
  `auth` text,
  `body` text,
  `events` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `postman_requests`
--

INSERT INTO `postman_requests` (`id`, `collection_id`, `request_name`, `method`, `url`, `headers`, `auth`, `body`, `events`) VALUES
(1, 1, 'Login PREPRO', 'POST', 'http://dghpsh.agcontrol.gob.ar/preprod/ws.rest.habilitaciones/api/Authenticate', '[]', '{\"type\":\"basic\",\"basic\":[{\"key\":\"password\",\"value\":\"prueba123\",\"type\":\"string\"},{\"key\":\"username\",\"value\":\"WS-LIZA\",\"type\":\"string\"}]}', '[]', '[]'),
(2, 1, 'Login PRD', 'POST', 'https://dghpsh.agcontrol.gob.ar/ws.rest.habilitaciones/api/Authenticate', '[]', '{\"type\":\"basic\",\"basic\":[{\"key\":\"password\",\"value\":\"AGC-4525\",\"type\":\"string\"},{\"key\":\"username\",\"value\":\"WS-LIZA\",\"type\":\"string\"}]}', '[]', '[{\"listen\":\"test\",\"script\":{\"exec\":[\"var jsonData = pm.response.json();\\r\",\"\\r\",\"pm.environment.set(\\\"tokenSGI-PRD\\\", jsonData.Token);\"],\"type\":\"text\\/javascript\",\"packages\":[]}}]'),
(3, 1, 'getTramitesBySMPLiza', 'GET', 'http://dghpsh.agcontrol.gob.ar/ws.rest.habilitaciones/api/getTramitesBySMPLiza?seccion=65&manzana=013&parcela=032', '[{\"key\":\"token\",\"value\":\"{{tokenSGI-PRD}}\",\"type\":\"text\"}]', '[]', '[]', '[]'),
(4, 1, 'PREPRO getTramitesBySMPLiza', 'GET', 'http://dghpsh.agcontrol.gob.ar/preprod/ws.rest.habilitaciones/api/getTramitesBySMPLiza?seccion=37&manzana=020&parcela=007', '[{\"key\":\"token\",\"value\":\"7f47ba7d-7e82-4488-a2ae-ede360b72b3f\",\"type\":\"text\"}]', '[]', '[]', '[]'),
(5, 1, 'getTramite', 'GET', 'https://dghpsh.agcontrol.gob.ar/ws.rest.habilitaciones/api/GetTramite?idTipoTramite=1&idSolicitud=474149', '[{\"key\":\"token\",\"value\":\"bdb6f1cb-39cd-4ba4-89f0-453cfefef1f2\",\"type\":\"text\"}]', '[]', '[]', '[]'),
(6, 1, 'getTramite PREPRO', 'GET', 'http://dghpsh.agcontrol.gob.ar/preprod/ws.rest.habilitaciones/api/GetTramite?idTipoTramite=2&idSolicitud=417', '[{\"key\":\"token\",\"value\":\"9cb95230-0d1c-4a4b-a568-5a5548f15295\",\"type\":\"text\"}]', '[]', '[]', '[]'),
(7, 1, 'getTramite PREPRO LD', 'GET', 'http://dghpsh.agcontrol.gob.ar/preprod/ws.rest.habilitaciones/api/getTramitesLD?idSolicitud=439506&tipoTramite=1&historico=false', '[{\"key\":\"token\",\"value\":\"fa4bff1d-cd55-4f87-9b36-ba32361d1a8c\",\"type\":\"text\"}]', '[]', '[]', '[]'),
(8, 1, 'getTramiteLD PRD', 'GET', 'http://dghpsh.agcontrol.gob.ar/preprod/ws.rest.habilitaciones/api/GetTramite?idTipoTramite=2&idSolicitud=417', '[{\"key\":\"token\",\"value\":\"9cb95230-0d1c-4a4b-a568-5a5548f15295\",\"type\":\"text\"}]', '[]', '[]', '[]');

-- --------------------------------------------------------

--
-- Table structure for table `request_logs`
--

CREATE TABLE `request_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Clemente', 'admin@mail.com', NULL, '$2y$12$9KXRYVjDHQ8S.port5NNVuXF7FIuGe7U19g20/tHtPuVpKujsJEkS', '8xBAlBrCxUdwO9BmbbC9wkYoyeNcIdh4ZV8HIiJd8gnSs8ciwpiJmdTRI5ku', '2024-10-08 03:58:00', '2024-10-08 04:13:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `postman_collections`
--
ALTER TABLE `postman_collections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `postman_requests`
--
ALTER TABLE `postman_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `collection_id` (`collection_id`);

--
-- Indexes for table `request_logs`
--
ALTER TABLE `request_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `postman_collections`
--
ALTER TABLE `postman_collections`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `postman_requests`
--
ALTER TABLE `postman_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `request_logs`
--
ALTER TABLE `request_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `postman_requests`
--
ALTER TABLE `postman_requests`
  ADD CONSTRAINT `postman_requests_ibfk_1` FOREIGN KEY (`collection_id`) REFERENCES `postman_collections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
