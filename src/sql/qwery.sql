CREATE TABLE cliente (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(15),
    direccion VARCHAR(255) NOT NULL,
    tipo_cliente VARCHAR(20) NOT NULL,
    CHECK (tipo_cliente IN ('NATURAL', 'JURIDICA'))
) ENGINE=InnoDB;

CREATE TABLE persona_natural (
    id_cliente INT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    cedula VARCHAR(20) NOT NULL UNIQUE,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE persona_juridica (
    id_cliente INT PRIMARY KEY,
    razon_social VARCHAR(200) NOT NULL,
    ruc VARCHAR(20) NOT NULL UNIQUE,
    representante_legal VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE categoria (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estado TINYINT(1) NOT NULL DEFAULT TRUE,
    id_padre INT,
    FOREIGN KEY (id_padre) REFERENCES categoria(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE producto (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    stock INTEGER NOT NULL DEFAULT 0,
    id_categoria INT NOT NULL,
    tipo_producto VARCHAR(20) NOT NULL,
    CHECK (precio_unitario >= 0),
    CHECK (stock >= 0),
    CHECK (tipo_producto IN ('FISICO', 'DIGITAL')),
    FOREIGN KEY (id_categoria) REFERENCES categoria(id)
) ENGINE=InnoDB;

CREATE TABLE producto_fisico (
    id_producto INT PRIMARY KEY,
    peso DECIMAL(10, 2),
    alto DECIMAL(10, 2),
    ancho DECIMAL(10, 2),
    profundidad DECIMAL(10, 2),
    CHECK (peso >= 0),
    CHECK (alto >= 0),
    CHECK (ancho >= 0),
    CHECK (profundidad >= 0),
    FOREIGN KEY (id_producto) REFERENCES producto(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE producto_digital (
    id_producto INT PRIMARY KEY,
    url_descarga VARCHAR(255) NOT NULL,
    licencia VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES producto(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE venta (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_cliente INT NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    estado VARCHAR(50) NOT NULL,
    CHECK (total >= 0),
    CHECK (estado IN ('borrador', 'emitida', 'anulada')),
    FOREIGN KEY (id_cliente) REFERENCES cliente(id)
) ENGINE=InnoDB;

CREATE TABLE detalle_venta (
    id_venta INT NOT NULL,
    line_number INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (id_venta, line_number),
    CHECK (cantidad > 0),
    CHECK (precio_unitario >= 0),
    CHECK (subtotal >= 0),
    FOREIGN KEY (id_venta) REFERENCES venta(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES producto(id)
) ENGINE=InnoDB;

CREATE TABLE factura (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_venta INT UNIQUE NOT NULL,
    numero VARCHAR(50) UNIQUE NOT NULL,
    clave_acceso VARCHAR(100) UNIQUE,
    fecha_emision TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(50) NOT NULL,
    CHECK (estado IN ('emitida', 'anulada', 'pendiente_sri')),
    FOREIGN KEY (id_venta) REFERENCES venta(id)
) ENGINE=InnoDB;

CREATE TABLE usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    estado TINYINT(1) NOT NULL DEFAULT TRUE
) ENGINE=InnoDB;

CREATE TABLE rol (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE permiso (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE rol_permiso (
    id_rol INT NOT NULL,
    id_permiso INT NOT NULL,
    PRIMARY KEY (id_rol, id_permiso),
    FOREIGN KEY (id_rol) REFERENCES rol(id) ON DELETE CASCADE,
    FOREIGN KEY (id_permiso) REFERENCES permiso(id) ON DELETE CASCADE
) ENGINE=InnoDB;


--
-- Datos para la tabla `cliente` (10 registros)
--
INSERT INTO cliente (id, email, telefono, direccion, tipo_cliente) VALUES
(1, 'juan.perez@example.com', '0987654321', 'Av. Siempre Viva 123, Quito', 'NATURAL'),
(2, 'maria.gomez@example.com', '0991234567', 'Calle Luna 456, Guayaquil', 'NATURAL'),
(3, 'pedro.ruiz@example.com', '0965432109', 'Jr. Sol 789, Cuenca', 'NATURAL'),
(4, 'ana.lopez@example.com', '0981122334', 'Blvd. Palmeras 101, Manta', 'NATURAL'),
(5, 'carlos.sanchez@example.com', '0978877665', 'Res. Montañas 202, Ambato', 'NATURAL'),
(6, 'info@techsolutions.com', '0950001111', 'Calle Empresarial 10, Quito', 'JURIDICA'),
(7, 'ventas@innoapp.com', '0942223333', 'Av. Innovación 50, Guayaquil', 'JURIDICA'),
(8, 'contacto@logisticsfast.com', '0935556666', 'Via Rapida 30, Cuenca', 'JURIDICA'),
(9, 'hr@globalpartners.com', '0927778888', 'Paseo Internacional 40, Manta', 'JURIDICA'),
(10, 'support@digitalworld.com', '0919990000', 'Zona Virtual 60, Loja', 'JURIDICA');

--
-- Datos para la tabla `persona_natural` (5 registros vinculados a clientes NATURAL)
--
INSERT INTO persona_natural (id_cliente, nombres, apellidos, cedula) VALUES
(1, 'Juan', 'Pérez García', '1710101010'),
(2, 'María', 'Gómez Herrera', '0920202020'),
(3, 'Pedro', 'Ruiz Morales', '0730303030'),
(4, 'Ana', 'López Castro', '1340404040'),
(5, 'Carlos', 'Sánchez Díaz', '0650505050');

--
-- Datos para la tabla `persona_juridica` (5 registros vinculados a clientes JURIDICA)
--
INSERT INTO persona_juridica (id_cliente, razon_social, ruc, representante_legal) VALUES
(6, 'Tech Solutions S.A.', '1791000000001', 'Luis Mendoza'),
(7, 'InnoApp C. Ltda.', '0992000000001', 'Gabriela Silva'),
(8, 'Logistics Fast S.A.S.', '0793000000001', 'Ricardo Vallejo'),
(9, 'Global Partners Corp.', '1394000000001', 'Patricia Ortega'),
(10, 'Digital World E.I.R.L.', '1195000000001', 'Fernando Torres');

--
-- Datos para la tabla `categoria` (10 registros, con subcategorías)
--
INSERT INTO categoria (id, nombre, descripcion, estado, id_padre) VALUES
(1, 'Electrónica', 'Dispositivos y accesorios electrónicos', TRUE, NULL),
(2, 'Computadoras', 'Portátiles, desktops y componentes', TRUE, 1),
(3, 'Smartphones', 'Teléfonos inteligentes y accesorios', TRUE, 1),
(4, 'Software', 'Programas y aplicaciones informáticas', TRUE, NULL),
(5, 'Videojuegos', 'Juegos y consolas', TRUE, 4),
(6, 'Libros', 'Libros físicos y digitales de diversas temáticas', TRUE, NULL),
(7, 'Novelas', 'Ficción y literatura', TRUE, 6),
(8, 'Educación', 'Cursos y materiales educativos', TRUE, NULL),
(9, 'Servicios', 'Servicios profesionales y técnicos', TRUE, NULL),
(10, 'Diseño Gráfico', 'Servicios de creación de contenido visual', TRUE, 9);

--
-- Datos para la tabla `producto` (10 registros, mixtos físicos y digitales)
--
INSERT INTO producto (id, nombre, descripcion, precio_unitario, stock, id_categoria, tipo_producto) VALUES
(1, 'Laptop UltraBook XYZ', 'Portátil ligero con procesador de última generación', 1200.00, 15, 2, 'FISICO'),
(2, 'Smartphone Galaxy S20', 'Teléfono móvil de alta gama con cámara profesional', 850.50, 25, 3, 'FISICO'),
(3, 'Licencia Office Pro 2024', 'Suite de ofimática completa para productividad', 299.99, 100, 4, 'DIGITAL'),
(4, 'Ebook "El Gran Viaje"', 'Novela de aventura y misterio en formato digital', 15.00, 500, 7, 'DIGITAL'),
(5, 'Monitor Curvo 27 pulgadas', 'Monitor gaming con alta tasa de refresco', 350.75, 10, 2, 'FISICO'),
(6, 'Antivirus Total Security', 'Protección completa contra amenazas en línea', 49.99, 200, 4, 'DIGITAL'),
(7, 'Curso Online Programación Web', 'Curso interactivo de desarrollo web full-stack', 199.00, 300, 8, 'DIGITAL'),
(8, 'Teclado Mecánico RGB', 'Teclado para gaming con retroiluminación personalizable', 99.00, 20, 2, 'FISICO'),
(9, 'Juego "Aventura Espacial"', 'Videojuego de exploración espacial para PC', 59.99, 150, 5, 'DIGITAL'),
(10, 'Impresora Multifunción HP', 'Impresora, escáner y copiadora para oficina', 180.20, 8, 1, 'FISICO');

--
-- Datos para la tabla `producto_fisico` (6 registros vinculados a productos FISICO)
--
INSERT INTO producto_fisico (id_producto, peso, alto, ancho, profundidad) VALUES
(1, 1.5, 2.0, 35.0, 24.0),
(2, 0.2, 0.8, 7.5, 15.0),
(5, 5.0, 40.0, 60.0, 20.0),
(8, 1.2, 3.0, 45.0, 15.0),
(10, 6.5, 30.0, 40.0, 35.0);

--
-- Datos para la tabla `producto_digital` (4 registros vinculados a productos DIGITAL)
--
INSERT INTO producto_digital (id_producto, url_descarga, licencia) VALUES
(3, 'https://descargas.example.com/office-pro-2024.zip', 'LIC-OP2024-XYZ789'),
(4, 'https://descargas.example.com/el-gran-viaje.pdf', 'LIC-EGV-ABC123'),
(6, 'https://descargas.example.com/antivirus-ts.exe', 'LIC-AVTS-QWE456'),
(7, 'https://plataforma.example.com/curso-web', 'LIC-CW-RTY901'),
(9, 'https://descargas.example.com/aventura-espacial.zip', 'LIC-AE-ASD234');

--
-- Datos para la tabla `venta` (10 registros)
--
INSERT INTO venta (id, fecha, id_cliente, total, estado) VALUES
(1, '2024-07-20 10:30:00', 1, 1200.00, 'emitida'), -- Cliente Natural - Laptop
(2, '2024-07-21 14:00:00', 2, 850.50, 'emitida'),  -- Cliente Natural - Smartphone
(3, '2024-07-22 09:15:00', 6, 2999.90, 'emitida'), -- Cliente Juridica - 10 licencias Office
(4, '2024-07-23 11:45:00', 3, 30.00, 'emitida'),  -- Cliente Natural - 2 Ebooks
(5, '2024-07-24 16:20:00', 7, 701.50, 'emitida'),  -- Cliente Juridica - Monitor y Antivirus
(6, '2024-07-25 08:00:00', 4, 199.00, 'emitida'),  -- Cliente Natural - Curso Programacion Web
(7, '2024-07-26 13:00:00', 8, 990.00, 'emitida'),  -- Cliente Juridica - 10 Teclados
(8, '2024-07-27 10:00:00', 5, 119.98, 'emitida'),  -- Cliente Natural - 2 Juegos Aventura Espacial
(9, '2024-07-28 15:00:00', 9, 360.40, 'emitida'),  -- Cliente Juridica - 2 Impresoras
(10, '2024-07-29 11:00:00', 1, 49.99, 'borrador'); -- Cliente Natural - Antivirus (borrador)

--
-- Datos para la tabla `detalle_venta` (ejemplos, vinculados a ventas y productos)
--
INSERT INTO detalle_venta (id_venta, line_number, id_producto, cantidad, precio_unitario, subtotal) VALUES
(1, 1, 1, 1, 1200.00, 1200.00), -- Venta 1: Laptop
(2, 1, 2, 1, 850.50, 850.50),   -- Venta 2: Smartphone
(3, 1, 3, 10, 299.99, 2999.90), -- Venta 3: 10 licencias Office
(4, 1, 4, 2, 15.00, 30.00),     -- Venta 4: 2 Ebooks
(5, 1, 5, 1, 350.75, 350.75),   -- Venta 5: Monitor Curvo
(5, 2, 6, 1, 49.99, 49.99),     -- Venta 5: Antivirus
(6, 1, 7, 1, 199.00, 199.00),   -- Venta 6: Curso Programacion Web
(7, 1, 8, 10, 99.00, 990.00),   -- Venta 7: 10 Teclados
(8, 1, 9, 2, 59.99, 119.98),    -- Venta 8: 2 Juegos Aventura Espacial
(9, 1, 10, 2, 180.20, 360.40),  -- Venta 9: 2 Impresoras
(10, 1, 6, 1, 49.99, 49.99);    -- Venta 10: Antivirus

--
-- Datos para la tabla `factura` (9 registros vinculados a ventas emitidas)
--
INSERT INTO factura (id, id_venta, numero, clave_acceso, fecha_emision, estado) VALUES
(1, 1, '001-001-000000001', 'CLV-ACC-000000001', '2024-07-20 10:35:00', 'emitida'),
(2, 2, '001-001-000000002', 'CLV-ACC-000000002', '2024-07-21 14:05:00', 'emitida'),
(3, 3, '001-001-000000003', 'CLV-ACC-000000003', '2024-07-22 09:20:00', 'emitida'),
(4, 4, '001-001-000000004', 'CLV-ACC-000000004', '2024-07-23 11:50:00', 'emitida'),
(5, 5, '001-001-000000005', 'CLV-ACC-000000005', '2024-07-24 16:25:00', 'emitida'),
(6, 6, '001-001-000000006', 'CLV-ACC-000000006', '2024-07-25 08:05:00', 'emitida'),
(7, 7, '001-001-000000007', 'CLV-ACC-000000007', '2024-07-26 13:05:00', 'emitida'),
(8, 8, '001-001-000000008', 'CLV-ACC-000000008', '2024-07-27 10:05:00', 'emitida'),
(9, 9, '001-001-000000009', 'CLV-ACC-000000009', '2024-07-28 15:05:00', 'emitida');

--
-- Datos para la tabla `usuario` (10 registros)
--
INSERT INTO usuario (id, username, password_hash, estado) VALUES
(1, 'admin', 'hash_admin_123', TRUE),
(2, 'vendedor1', 'hash_vendedor_abc', TRUE),
(3, 'gerente_ventas', 'hash_gerente_xyz', TRUE),
(4, 'contabilidad', 'hash_cont_789', TRUE),
(5, 'inventario', 'hash_inv_pqr', TRUE),
(6, 'soporte', 'hash_soporte_mno', TRUE),
(7, 'cliente_web', 'hash_cliente_web_1', TRUE),
(8, 'auditor', 'hash_auditor_def', TRUE),
(9, 'marketing', 'hash_mkt_ghi', TRUE),
(10, 'logistica', 'hash_log_jkl', TRUE);

--
-- Datos para la tabla `rol` (4 registros)
--
INSERT INTO rol (id, nombre) VALUES
(1, 'Administrador'),
(2, 'Vendedor'),
(3, 'Contador'),
(4, 'Almacenista');

--
-- Datos para la tabla `permiso` (10 registros)
--
INSERT INTO permiso (id, codigo) VALUES
(1, 'crear_producto'),
(2, 'editar_producto'),
(3, 'eliminar_producto'),
(4, 'ver_ventas'),
(5, 'gestionar_usuarios'),
(6, 'generar_reportes'),
(7, 'emitir_facturas'),
(8, 'gestionar_clientes'),
(9, 'ver_inventario'),
(10, 'anular_ventas');

--
-- Datos para la tabla `rol_permiso` (ejemplos de asignaciones)
--
INSERT INTO rol_permiso (id_rol, id_permiso) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8), (1, 9), (1, 10), -- Administrador tiene todos los permisos
(2, 4), (2, 7), (2, 8), (2, 10),                                                 -- Vendedor puede ver ventas, emitir facturas, gestionar clientes, anular ventas
(3, 4), (3, 6), (3, 7),                                                           -- Contador puede ver ventas, generar reportes, emitir facturas
(4, 1), (4, 2), (4, 9);                                                           -- Almacenista puede crear, editar productos y ver inventario
