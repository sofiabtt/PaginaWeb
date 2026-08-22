-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 17, 2026 at 04:47 AM
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
-- Database: `Aerolineas`
--

-- --------------------------------------------------------

--
-- Table structure for table `Aerolineas`
--

CREATE TABLE `Aerolineas` (
  `codAerolinea` int(11) NOT NULL,
  `nombreAerolinea` varchar(100) NOT NULL,
  `codigoIATA` varchar(3) NOT NULL,
  `descripcionAerolinea` varchar(200) NOT NULL,
  `codPais` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Novedades`
--

CREATE TABLE `Novedades` (
  `codNovedad` int(11) NOT NULL,
  `textoNovedad` varchar(200) NOT NULL,
  `fechaPublicacionNovedad` varchar(10) NOT NULL,
  `fechaExpiracionNovedad` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Promociones`
--

CREATE TABLE `Promociones` (
  `codPromocion` int(11) NOT NULL,
  `descripcionPromocion` varchar(200) NOT NULL,
  `descuentoPromocion` decimal(10,0) NOT NULL,
  `codAerolinea` int(11) NOT NULL,
  `estadoPromocion` varchar(20) NOT NULL DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Reservas`
--

CREATE TABLE `Reservas` (
  `codReserva` int(11) NOT NULL,
  `codUsuario` int(11) NOT NULL,
  `codVuelo` int(11) NOT NULL,
  `fechaReservae` varchar(10) NOT NULL,
  `estadoReserva` varchar(20) NOT NULL DEFAULT 'PendienteDePago'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Usuarios`
--

CREATE TABLE `Usuarios` (
  `codUsuario` int(11) NOT NULL,
  `nombreUsuario` varchar(100) NOT NULL,
  `claveUsuario` varchar(255) NOT NULL,
  `tipoUsuario` varchar(20) NOT NULL,
  `emailUsuario` varchar(100) NOT NULL,
  `telefonoUsuario` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Usuarios`
--

INSERT INTO `Usuarios` (`codUsuario`, `nombreUsuario`, `claveUsuario`, `tipoUsuario`, `emailUsuario`, `telefonoUsuario`) VALUES
(1, 'Sofia Benetti', '$2y$10$ySS3WX0s/z2c5Pm0hJ4lRuenEzL4YNeNyooHDSjnuwpugxx9UD6he', 'administrador', 'sofiagibe@gmail.com', '3410000000');

-- --------------------------------------------------------

--
-- Table structure for table `Vuelos`
--

CREATE TABLE `Vuelos` (
  `codVuelo` int(11) NOT NULL,
  `codAerolinea` int(11) NOT NULL,
  `origenVuelo` varchar(50) NOT NULL,
  `destinoVuelo` varchar(50) NOT NULL,
  `fechaSalidaVuelo` varchar(10) NOT NULL,
  `horaSalidaVuelo` varchar(5) NOT NULL,
  `precioVuelo` decimal(10,0) NOT NULL,
  `asientosDisponibles` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Aerolineas`
--
ALTER TABLE `Aerolineas`
  ADD PRIMARY KEY (`codAerolinea`);

--
-- Indexes for table `Novedades`
--
ALTER TABLE `Novedades`
  ADD PRIMARY KEY (`codNovedad`);

--
-- Indexes for table `Promociones`
--
ALTER TABLE `Promociones`
  ADD PRIMARY KEY (`codPromocion`);

--
-- Indexes for table `Reservas`
--
ALTER TABLE `Reservas`
  ADD PRIMARY KEY (`codReserva`);

--
-- Indexes for table `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`codUsuario`);

--
-- Indexes for table `Vuelos`
--
ALTER TABLE `Vuelos`
  ADD PRIMARY KEY (`codVuelo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Aerolineas`
--
ALTER TABLE `Aerolineas`
  MODIFY `codAerolinea` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Novedades`
--
ALTER TABLE `Novedades`
  MODIFY `codNovedad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Promociones`
--
ALTER TABLE `Promociones`
  MODIFY `codPromocion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Reservas`
--
ALTER TABLE `Reservas`
  MODIFY `codReserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `codUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Vuelos`
--
ALTER TABLE `Vuelos`
  MODIFY `codVuelo` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
