-- Ejecutar una sola vez en phpMyAdmin, dentro de la base de datos aerolineas.
-- Conserva el precio efectivamente abonado aunque después cambie el vuelo.

ALTER TABLE Reservas
ADD COLUMN precioFinalReserva DECIMAL(10,2) NOT NULL DEFAULT 0
AFTER estadoReserva;

-- Impide cuentas duplicadas incluso si dos solicitudes llegan al mismo tiempo.
ALTER TABLE Usuarios
ADD UNIQUE KEY uk_usuarios_email (emailUsuario);

