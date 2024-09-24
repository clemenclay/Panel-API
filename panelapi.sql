-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 24, 2024 at 01:18 AM
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
-- Database: `panelapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_logs`
--

CREATE TABLE `api_logs` (
  `id` int NOT NULL,
  `api_name` varchar(255) NOT NULL,
  `api_url` varchar(255) NOT NULL,
  `status_code` int NOT NULL,
  `response` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `api_user` varchar(255) DEFAULT NULL,
  `api_password` varchar(255) DEFAULT NULL,
  `json_body` text,
  `http_method` varchar(10) NOT NULL,
  `execution_interval` int NOT NULL,
  `api_headers` text,
  `request` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `api_logs`
--

INSERT INTO `api_logs` (`id`, `api_name`, `api_url`, `status_code`, `response`, `created_at`, `api_user`, `api_password`, `json_body`, `http_method`, `execution_interval`, `api_headers`, `request`) VALUES
(27, 'WS LOGIN LIZA', 'http://ws.liza.agcontrol.gob.ar/api/account/login', 400, '{\"Resultado\":\"Error\",\"Mensaje\":\"Debe indicar usuario y/o clave.\"}', '2024-09-24 01:07:35', NULL, NULL, NULL, 'POST', 1, NULL, '{\"url\":\"http:\\/\\/ws.liza.agcontrol.gob.ar\\/api\\/account\\/login\",\"method\":\"POST\",\"body\":{\"nombreUsuario\":\"\",\"clave\":\"\"},\"params\":{\"nombreUsuario\":\"\",\"clave\":\"\"},\"user\":\"\"}');

-- --------------------------------------------------------

--
-- Table structure for table `estado_apis`
--

CREATE TABLE `estado_apis` (
  `id` int NOT NULL,
  `nombre_api` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `horario` datetime DEFAULT NULL,
  `id_api` int DEFAULT NULL,
  `log_message` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_logs`
--
ALTER TABLE `api_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estado_apis`
--
ALTER TABLE `estado_apis`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_logs`
--
ALTER TABLE `api_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `estado_apis`
--
ALTER TABLE `estado_apis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=660;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
