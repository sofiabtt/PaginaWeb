-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 13, 2026 at 03:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


--
-- Base de datos
--

CREATE DATABASE IF NOT EXISTS `Nuvia`;

USE `Nuvia`;


-- =====================================================
-- TABLA: Paises
-- =====================================================

CREATE TABLE `Paises` (

    `codPais` int(11) NOT NULL AUTO_INCREMENT,

    `nombrePais` varchar(100) NOT NULL,

    `codigoISO` varchar(2) NOT NULL UNIQUE,

    PRIMARY KEY (`codPais`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


-- =====================================================
-- TABLA: Ciudades
-- =====================================================

CREATE TABLE `Ciudades` (

    `codCiudad` int(11) NOT NULL AUTO_INCREMENT,

    `nombreCiudad` varchar(150) NOT NULL,

    `codPais` int(11) NOT NULL,

    PRIMARY KEY (`codCiudad`),

    CONSTRAINT `fk_ciudades_pais`
        FOREIGN KEY (`codPais`)
        REFERENCES `Paises` (`codPais`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


-- =====================================================
-- TABLA: Aeropuertos
-- =====================================================

CREATE TABLE `Aeropuertos` (

    `codAeropuerto` int(11) NOT NULL AUTO_INCREMENT,

    `codigoIATA` varchar(3) NOT NULL UNIQUE,

    `codigoICAO` varchar(4),

    `nombreAeropuerto` varchar(200) NOT NULL,

    `codCiudad` int(11) NOT NULL,

    PRIMARY KEY (`codAeropuerto`),

    CONSTRAINT `fk_aeropuertos_ciudad`
        FOREIGN KEY (`codCiudad`)
        REFERENCES `Ciudades` (`codCiudad`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


-- =====================================================
-- TABLA: Usuarios
-- =====================================================

CREATE TABLE `Usuarios` (

    `codUsuario` int(11) NOT NULL AUTO_INCREMENT,

    `nombreUsuario` varchar(100) NOT NULL,

    `claveUsuario` varchar(300) DEFAULT NULL,

    `tipoUsuario` varchar(20) NOT NULL,

    `emailUsuario` varchar(100) NOT NULL,

    `telefonoUsuario` varchar(20) NOT NULL,

    `verificado` tinyint(1) NOT NULL DEFAULT 0,

    `tokenVerificacion` varchar(100) DEFAULT NULL,

    `fechaVerificacion` datetime DEFAULT NULL,

    `activoUsuario` TINYINT(1) NOT NULL DEFAULT 1,

    `fechaEliminacion` DATETIME DEFAULT NULL,

    PRIMARY KEY (`codUsuario`),

    UNIQUE KEY `uk_usuarios_email` (`emailUsuario`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


-- =====================================================
-- TABLA: Aerolineas
-- =====================================================

CREATE TABLE `Aerolineas` (

    `codAerolinea` int(11) NOT NULL AUTO_INCREMENT,

    `nombreAerolinea` varchar(100) NOT NULL,

    `codigoIATA` varchar(3) NOT NULL,

    `descripcionAerolinea` varchar(200) NOT NULL,

    `codPais` int(11) NOT NULL,

    `activoAerolinea` tinyint(1) NOT NULL DEFAULT 1,

    `fechaEliminacion` datetime DEFAULT NULL,

    `codUsuario` int(11) DEFAULT NULL,

    PRIMARY KEY (`codAerolinea`),

    CONSTRAINT `fk_aerolineas_pais`
        FOREIGN KEY (`codPais`)
        REFERENCES `Paises` (`codPais`),

    CONSTRAINT `fk_aerolineas_usuario`
        FOREIGN KEY (`codUsuario`)
        REFERENCES `Usuarios` (`codUsuario`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


-- =====================================================
-- TABLA: Novedades
-- =====================================================

CREATE TABLE `Novedades` (

    `codNovedad` int(11) NOT NULL AUTO_INCREMENT,

    `textoNovedad` varchar(200) NOT NULL,

    `fechaPublicacionNovedad` varchar(10) NOT NULL,

    `fechaExpiracionNovedad` varchar(10) NOT NULL,

    `activoNovedad` tinyint(1) NOT NULL DEFAULT 1,

    `fechaEliminacion` datetime DEFAULT NULL,

    PRIMARY KEY (`codNovedad`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


-- =====================================================
-- TABLA: Promociones
-- =====================================================

CREATE TABLE `Promociones` (

    `codPromocion` int(11) NOT NULL AUTO_INCREMENT,

    `descripcionPromocion` varchar(200) NOT NULL,

    `descuentoPromocion` decimal(10,0) NOT NULL,

    `codAerolinea` int(11) NOT NULL,

    `estadoPromocion` varchar(20) NOT NULL DEFAULT 'Pendiente',

    `activoPromocion` tinyint(1) NOT NULL DEFAULT 1,

    `fechaEliminacion` datetime DEFAULT NULL,

    PRIMARY KEY (`codPromocion`),

    CONSTRAINT `fk_promociones_aerolinea`
        FOREIGN KEY (`codAerolinea`)
        REFERENCES `Aerolineas` (`codAerolinea`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


-- =====================================================
-- TABLA: Vuelos
-- =====================================================

CREATE TABLE `Vuelos` (

    `codVuelo` int(11) NOT NULL AUTO_INCREMENT,

    `codAerolinea` int(11) NOT NULL,

    `origenVuelo` varchar(200) NOT NULL,

    `destinoVuelo` varchar(200) NOT NULL,

    `fechaSalidaVuelo` varchar(10) NOT NULL,

    `horaSalidaVuelo` varchar(5) NOT NULL,

    `precioVuelo` decimal(10,0) NOT NULL,

    `asientosDisponibles` int(11) NOT NULL,

    `activoVuelo` tinyint(1) NOT NULL DEFAULT 1,

    `fechaEliminacion` datetime DEFAULT NULL,

    PRIMARY KEY (`codVuelo`),

    CONSTRAINT `fk_vuelos_aerolinea`
        FOREIGN KEY (`codAerolinea`)
        REFERENCES `Aerolineas` (`codAerolinea`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


-- =====================================================
-- TABLA: Reservas
-- =====================================================

CREATE TABLE `Reservas` (

    `codReserva` int(11) NOT NULL AUTO_INCREMENT,

    `codUsuario` int(11) NOT NULL,

    `codVuelo` int(11) NOT NULL,

    `fechaReservae` varchar(10) NOT NULL,

    `estadoReserva` varchar(20) NOT NULL DEFAULT 'PendienteDePago',

    `precioFinalReserva` decimal(10,2) NOT NULL DEFAULT 0,

    PRIMARY KEY (`codReserva`),

    CONSTRAINT `fk_reservas_usuario`
        FOREIGN KEY (`codUsuario`)
        REFERENCES `Usuarios` (`codUsuario`),

    CONSTRAINT `fk_reservas_vuelo`
        FOREIGN KEY (`codVuelo`)
        REFERENCES `Vuelos` (`codVuelo`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


-- =====================================================
-- TABLA: Actividad
-- =====================================================

CREATE TABLE `Actividad` (

    `codActividad` int(11) NOT NULL AUTO_INCREMENT,

    `fechaActividad` datetime NOT NULL DEFAULT current_timestamp(),

    `usuarioActividad` varchar(100) NOT NULL,

    `accionActividad` varchar(200) NOT NULL,

    PRIMARY KEY (`codActividad`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


-- =====================================================
-- DATOS DE PAISES
-- =====================================================

INSERT INTO `Paises`
(
    `nombrePais`,
    `codigoISO`
)
VALUES
('Argentina', 'AR'),
('Brasil', 'BR'),
('Chile', 'CL'),
('Uruguay', 'UY'),
('Paraguay', 'PY'),
('Estados Unidos', 'US'),
('México', 'MX'),
('España', 'ES'),
('Francia', 'FR'),
('Italia', 'IT');


-- =====================================================
-- DATOS DE CIUDADES
-- =====================================================

INSERT INTO `Ciudades`
(
    `nombreCiudad`,
    `codPais`
)
VALUES
('Buenos Aires', 1),
('Rosario', 1),
('Córdoba', 1),
('Mendoza', 1),

('São Paulo', 2),
('Río de Janeiro', 2),

('Santiago', 3),

('Montevideo', 4),

('Asunción', 5),

('Miami', 6),
('Nueva York', 6),
('Los Ángeles', 6),

('Ciudad de México', 7),
('Cancún', 7),

('Madrid', 8),
('Barcelona', 8),

('París', 9),

('Roma', 10),
('Milán', 10);


-- =====================================================
-- DATOS DE AEROPUERTOS
-- =====================================================

INSERT INTO `Aeropuertos`
(
    `codigoIATA`,
    `codigoICAO`,
    `nombreAeropuerto`,
    `codCiudad`
)
VALUES
('EZE', 'SAEZ', 'Aeropuerto Internacional Ministro Pistarini', 1),
('AEP', 'SABE', 'Aeroparque Jorge Newbery', 1),
('ROS', 'SAAR', 'Aeropuerto Internacional Rosario', 2),
('COR', 'SACO', 'Aeropuerto Internacional Ingeniero Ambrosio Taravella', 3),
('MDZ', 'SAME', 'Aeropuerto Internacional Gobernador Francisco Gabrielli', 4),

('GRU', 'SBGR', 'Aeropuerto Internacional de São Paulo-Guarulhos', 5),
('GIG', 'SBGL', 'Aeropuerto Internacional de Río de Janeiro-Galeão', 6),

('SCL', 'SCEL', 'Aeropuerto Internacional Arturo Merino Benítez', 7),

('MVD', 'SUMU', 'Aeropuerto Internacional de Carrasco', 8),

('ASU', 'SGAS', 'Aeropuerto Internacional Silvio Pettirossi', 9),

('MIA', 'KMIA', 'Aeropuerto Internacional de Miami', 10),
('JFK', 'KJFK', 'Aeropuerto Internacional John F. Kennedy', 11),
('LAX', 'KLAX', 'Aeropuerto Internacional de Los Ángeles', 12),

('MEX', 'MMMX', 'Aeropuerto Internacional Benito Juárez', 13),
('CUN', 'MMUN', 'Aeropuerto Internacional de Cancún', 14),

('MAD', 'LEMD', 'Aeropuerto Adolfo Suárez Madrid-Barajas', 15),
('BCN', 'LEBL', 'Aeropuerto Josep Tarradellas Barcelona-El Prat', 16),

('CDG', 'LFPG', 'Aeropuerto Charles de Gaulle', 17),

('FCO', 'LIRF', 'Aeropuerto Internacional Leonardo da Vinci', 18),
('MXP', 'LIMC', 'Aeropuerto de Milán-Malpensa', 19);


-- =====================================================
-- DATOS DE USUARIOS
-- =====================================================

INSERT INTO `Usuarios`
(
    `codUsuario`,
    `nombreUsuario`,
    `claveUsuario`,
    `tipoUsuario`,
    `emailUsuario`,
    `telefonoUsuario`,
    `verificado`,
    `tokenVerificacion`
)
VALUES
(
    1,
    'Sofia Benetti',
    '$2y$10$gPTxhUcMQw5x2W0wgKYY3.hYCIQk.xPgwzhoJwQ2Plrq4/sMyILy.',
    'administrador',
    'sofiagibe@gmail.com',
    '3410000000',
    1,
    NULL
);


COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;