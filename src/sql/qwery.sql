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