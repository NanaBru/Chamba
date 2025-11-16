
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

CREATE DATABASE IF NOT EXISTS chambabd
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE chambabd;


CREATE TABLE usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) DEFAULT NULL,
  apellido VARCHAR(100) DEFAULT NULL,
  edad INT DEFAULT NULL,
  telefono VARCHAR(20) UNIQUE,
  email VARCHAR(100) UNIQUE,

  
  password VARCHAR(255) DEFAULT NULL,
  rol ENUM('usuario', 'administrador') DEFAULT 'usuario',
  foto_perfil VARCHAR(255) NOT NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  descripcion TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  descripcion TEXT DEFAULT NULL,
  icono VARCHAR(50) DEFAULT '📋',
  activo TINYINT(1) DEFAULT 1,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE publicaciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  categoria_id INT NULL,
  titulo VARCHAR(150) NOT NULL,
  descripcion TEXT NOT NULL,
  imagen VARCHAR(255) DEFAULT NULL,
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE calificasion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  publicacion_id INT NOT NULL,
  usuario_id INT NOT NULL,
  estrellas TINYINT NOT NULL CHECK (estrellas BETWEEN 1 AND 5),
  comentario TEXT DEFAULT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (publicacion_id) REFERENCES publicaciones(id) ON DELETE CASCADE,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE mensajes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  emisor_id INT NOT NULL,
  receptor_id INT NOT NULL,
  mensaje TEXT NOT NULL,
  leido TINYINT(1) DEFAULT 0,
  fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (emisor_id) REFERENCES usuario(id) ON DELETE CASCADE,
  FOREIGN KEY (receptor_id) REFERENCES usuario(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE solicitudes_resena (
  id INT AUTO_INCREMENT PRIMARY KEY,
  publicacion_id INT NOT NULL,
  proveedor_id INT NOT NULL,
  cliente_id INT NOT NULL,
  estado ENUM('pendiente','completada','rechazada') DEFAULT 'pendiente',
  fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY solicitud_unica (publicacion_id, cliente_id),
  FOREIGN KEY (publicacion_id) REFERENCES publicaciones(id) ON DELETE CASCADE,
  FOREIGN KEY (proveedor_id) REFERENCES usuario(id) ON DELETE CASCADE,
  FOREIGN KEY (cliente_id) REFERENCES usuario(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE reportes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tipo ENUM('mensaje','resena','publicacion') NOT NULL,
  referencia_id INT NOT NULL,
  reportado_por INT NOT NULL,
  motivo TEXT NOT NULL,
  estado ENUM('pendiente','revisado','resuelto') DEFAULT 'pendiente',
  fecha_reporte TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (reportado_por) REFERENCES usuario(id) ON DELETE CASCADE,
  INDEX idx_tipo (tipo),
  INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- Usuario Administrador
INSERT INTO usuario (nombre, apellido, edad, telefono, email, password, rol, descripcion)
VALUES ('Admin', 'Sistema', 30, '099000000', 'admin@chamba.com',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'administrador', 'Administrador del sistema Chamba');

-- Categorías Base
INSERT INTO categorias (nombre, descripcion, icono) VALUES
('Electricidad', 'Servicios de electricidad e instalaciones eléctricas', '⚡'),
('Plomería', 'Servicios de plomería y gasfitería', '🔧'),
('Carpintería', 'Servicios de carpintería y muebles', '🪚'),
('Pintura', 'Servicios de pintura y decoración', '🎨'),
('Albañilería', 'Servicios de construcción y albañilería', '🧱'),
('Limpieza', 'Servicios de limpieza y mantenimiento', '🧹'),
('Jardinería', 'Servicios de jardinería y paisajismo', '🌳'),
('Tecnología', 'Servicios de informática y tecnología', '💻');

COMMIT;
