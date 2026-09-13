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
-- Database: `Aerolineas`
--

-- --------------------------------------------------------

--
-- Table structure for table `Actividad`
--

CREATE TABLE `Actividad` (
  `codActividad` int(11) NOT NULL,
  `fechaActividad` datetime NOT NULL DEFAULT current_timestamp(),
  `usuarioActividad` varchar(100) NOT NULL,
  `accionActividad` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `claveUsuario` varchar(300) NOT NULL,
  `tipoUsuario` varchar(20) NOT NULL,
  `emailUsuario` varchar(100) NOT NULL,
  `telefonoUsuario` varchar(20) NOT NULL,
  `verificado` tinyint(1) NOT NULL DEFAULT 0,
  `tokenVerificacion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Usuarios`
--

INSERT INTO `Usuarios` (`codUsuario`, `nombreUsuario`, `claveUsuario`, `tipoUsuario`, `emailUsuario`, `telefonoUsuario`, `verificado`, `tokenVerificacion`) VALUES
(1, 'Sofia Benetti', '$2y$10$gPTxhUcMQw5x2W0wgKYY3.hYCIQk.xPgwzhoJwQ2Plrq4/sMyILy.', 'administrador', 'sofiagibe@gmail.com', '3410000000', 1, NULL),
(2, 'Catalina Molina', '$2y$10$pywJMPHCrFnuQheG6pG8AOCSocfhRqlVN6CIsPQIfZKJuHWCKqCPO', 'usuario', 'hzcqpdmjxftw@tempmail.ai', '3416551111', 0, 'baa8f00799fa0f3c7cc184ea6b5be98b8d5a0175b707153d696c405055b931fe'),
(3, 'Camila Irina', '$2y$10$o1gLVlNGeoe6xM8DZsGbuO5jH5/yUKgW5FmWiHm7ioveUjI6GPcrG', 'usuario', 'l9pp8106zydd@tempmail.ai', '3416551212', 0, '8897502f04af47b7f04d8746632a88feb9af0cc2895d9eec7c9368c88be8916a'),
(4, 'Ricardo Fort', '$2y$10$EtmH0PfX4sxSWaMomcvVcO5fr39COClnLn0VBlYeasZlqxJ8WSPle', 'usuario', 'm2n0pi1uawfg@tempmail.ai', '3416666666', 0, '100eae1fdbad9258f4c495187a29bed274aef713549fc74d278a3e9a13162ac8'),
(5, 'Ricardo Fort', '$2y$10$a1NR1kugK33DXKEKheBVgOlPnlenBgNVAWf5o.gyz2Lieez/0GpoG', 'usuario', 'm2n0pi1uawfg@tempmail.ai', '3416666666', 0, '1eaf639be1073c436031a18d1fd10dad60b3a926ab8eb33acddaed525de126a7'),
(6, 'Selena Gomez', '$2y$10$G0UZE3mxP8C5HTKdxSXsgO9SQZHnLCHmoL9nIVSWhrjY8NUpUjati', 'usuario', 'xelhrvrncbcy@tempmail.ai', '3214444444', 0, 'f183f17603b6b32df66ead310481150e009fcb76b25828b8f6b14d6405ea43de'),
(7, 'Candela Moria', '$2y$10$Q77NaMD2GRtbXi0CjGy/F.gIcZyQsYw/sY6ghEnc/AVcwcQ0npQh.', 'usuario', 'm5q16juwwk7d@tempmail.ai', '341555555', 1, '753281'),
(8, 'Madonna ', '$2y$10$xI.RJchgz/c4JKsNlAf9ke9CZzNwjrhU4yGsWYnMoPvhBan.x/zYG', 'usuario', '2gwsgkf65woy@tempmail.ai', '3416551213', 1, '135631'),
(9, 'Camila Benetti', '$2y$10$mvpzFoTwnkEeRh8ibUeq9.R0.KqUDwa31iBZWO0pDVqYxTC4lE7US', 'usuario', 'camibenetti4@gmail.com', '3415555555', 0, '990721');

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
-- Indexes for table `Actividad`
--
ALTER TABLE `Actividad`
  ADD PRIMARY KEY (`codActividad`);

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
-- AUTO_INCREMENT for table `Actividad`
--
ALTER TABLE `Actividad`
  MODIFY `codActividad` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `codUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `Vuelos`
--
ALTER TABLE `Vuelos`
  MODIFY `codVuelo` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
