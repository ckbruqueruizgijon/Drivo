-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para drivo
CREATE DATABASE IF NOT EXISTS `drivo` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `drivo`;

-- Volcando estructura para tabla drivo.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `passw` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `rol` enum('admin','cliente') NOT NULL DEFAULT 'cliente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla drivo.clientes: ~4 rows (aproximadamente)
INSERT INTO `clientes` (`id`, `usuario`, `passw`, `email`, `nombre`, `apellidos`, `rol`, `fecha_registro`) VALUES
	(1, 'admin', '$2y$10$Y1/YgO.5B/5Xf2v2L7aOVeS3o/H1R5J8tLgA91hL4rM/w9bVn28eO', 'admin@drivo.es', 'Admin', 'Principal', 'admin', '2026-05-02 09:50:04'),
	(2, 'alumno', '$2y$10$Y1/YgO.5B/5Xf2v2L7aOVeS3o/H1R5J8tLgA91hL4rM/w9bVn28eO', 'alumno@drivo.es', 'Estudiante', 'DAW', 'cliente', '2026-05-02 09:50:04'),
	(3, 'pepe@pepe.com', '$2y$10$BHPQdN/NepWgDNnlfuubQejarxw2GB7FrdsGg4J64McbMH9sNvEzq', 'pepe@pepe.com', 'pepe', 'pepe', 'cliente', '2026-05-03 09:07:09'),
	(5, 'adrianalvarezfer8@gmail.com', '$2y$10$MlrLQMBFaCDeHOdtZ1S6e.JIIRH52uWAGbA2SyE.fQjtakeyiZt3a', 'adrianalvarezfer8@gmail.com', 'Adrián', 'Álvarez Fernández', 'admin', '2026-05-10 20:53:33');

-- Volcando estructura para tabla drivo.flota
CREATE TABLE IF NOT EXISTS `flota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `matricula` varchar(15) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `motor` varchar(100) NOT NULL,
  `cambios` varchar(50) NOT NULL,
  `traccion` varchar(50) NOT NULL,
  `llantas` int(2) NOT NULL DEFAULT 17,
  `anio` int(4) NOT NULL,
  `precio_dia` decimal(8,2) NOT NULL DEFAULT 0.00,
  `imagen` varchar(255) DEFAULT 'default.jpg',
  `disponible` tinyint(1) NOT NULL DEFAULT 1,
  `oferta` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `matricula` (`matricula`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla drivo.flota: ~10 rows (aproximadamente)
INSERT INTO `flota` (`id`, `matricula`, `marca`, `modelo`, `motor`, `cambios`, `traccion`, `llantas`, `anio`, `precio_dia`, `imagen`, `disponible`, `oferta`) VALUES
	(1, '1111-AAA', 'Audi', 'A4', 'Gasolina 2.0 TFSI 197cv', 'Automática', 'a las 4 ruedas', 19, 2019, 75.00, 'audi_a4.avif', 1, 1),
	(2, '2222-BBB', 'Porsche', 'Cayenne', 'Gasolina V6 Biturbo 500cv', 'Automática', 'a las 4 ruedas', 21, 2023, 145.00, 'porsche_cayenne.jpg', 1, 1),
	(3, '3333-CCC', 'Volkswagen', 'Tiguan', 'Diésel 2.0 TDI 150cv', 'Automática', 'a las 4 ruedas', 19, 2018, 115.00, 'volkswagen_tiguan.webp', 1, 1),
	(4, '4444-DDD', 'Volkswagen', 'Golf', 'Gasolina 2.0 TFSI 241cv', 'Automática', 'a las 4 ruedas', 19, 2025, 99.00, 'volkswagen_golf_gti.avif', 1, 1),
	(5, '5555-EEE', 'Ford', 'Explorer', 'Gasolina 2.3 EcoBoost 300cv', 'Automática', 'a las 4 ruedas', 19, 2025, 69.00, 'ford_explorer.webp', 1, 1),
	(6, '6666-FFF', 'Mazda', 'CX-5', 'Gasolina 2.0 165cv', 'Manual 6v', 'Delantera', 19, 2021, 55.00, 'mazda_cx-5.webp', 1, 1),
	(7, '7777-GGG', 'Renault', 'Arkana', 'E-TECH Híbrido 140 CV', 'Automática', 'Delantera', 18, 2021, 65.00, 'renault_arkana.avif', 1, 1),
	(8, '8888-HHH', 'Peugeot', '3008', 'Diésel 1.5 BlueHDi 130cv', 'Automática', 'Delantera', 18, 2022, 35.00, 'peugeot_3008.webp', 1, 0),
	(9, '9999-JJJ', 'Citroën', 'C3', 'Gasolina 1.2 PureTech 82cv', 'Manual 6v', 'Delantera', 17, 2020, 29.00, 'citroen_c3.jpg', 1, 0),
	(11, '7430-CWX', 'Volkswagen', 'Golf V', 'Gasoil 2.0l TDI 140cv', 'Manual 6v', 'Delantera', 16, 2004, 600.00, 'volkswagen_golf_v.webp', 1, 0);

-- Volcando estructura para tabla drivo.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `codigo` varchar(6) NOT NULL,
  `expira_en` datetime NOT NULL,
  `usado` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla drivo.password_resets: ~0 rows (aproximadamente)

-- Volcando estructura para tabla drivo.reservas
CREATE TABLE IF NOT EXISTS `reservas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_vehiculo` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `sancion_km` decimal(8,2) NOT NULL DEFAULT 0.00,
  `sancion_tiempo` decimal(8,2) NOT NULL DEFAULT 0.00,
  `precio_total` decimal(10,2) NOT NULL,
  `estado` enum('Pendiente','Activa','Finalizada','Cancelada') NOT NULL DEFAULT 'Pendiente',
  PRIMARY KEY (`id`),
  KEY `idx_vehiculo` (`id_vehiculo`),
  KEY `idx_cliente` (`id_cliente`),
  CONSTRAINT `fk_reserva_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_reserva_vehiculo` FOREIGN KEY (`id_vehiculo`) REFERENCES `flota` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla drivo.reservas: ~1 rows (aproximadamente)
INSERT INTO `reservas` (`id`, `id_vehiculo`, `id_cliente`, `fecha_reserva`, `fecha_inicio`, `fecha_fin`, `sancion_km`, `sancion_tiempo`, `precio_total`, `estado`) VALUES
	(7, 1, 5, '2026-05-12 20:59:23', '2026-05-13', '2026-05-13', 150.00, 20.00, 150.00, 'Activa');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
