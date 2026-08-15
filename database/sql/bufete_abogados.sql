-- ============================================================================
-- BUFETE DE ABOGADOS - Esquema de base de datos relacional
-- MySQL 8.4
--
-- Requisito: "no se puede eliminar ningún registro de la base de datos"
-- Estrategia (doble capa):
--   1. Triggers BEFORE DELETE que impiden la eliminación física (SIGNAL 45000).
--   2. Columna deleted_at (soft delete) consumida por la aplicación Laravel.
-- ============================================================================

CREATE DATABASE IF NOT EXISTS bufete_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE bufete_db;

-- ----------------------------------------------------------------------------
-- 1. CLIENTES
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS caso_abogado;
DROP TABLE IF EXISTS casos;
DROP TABLE IF EXISTS abogados;
DROP TABLE IF EXISTS clientes;

CREATE TABLE clientes (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    cedula      VARCHAR(20)     NOT NULL,
    nombre      VARCHAR(100)    NOT NULL,
    apellido    VARCHAR(100)    NOT NULL,
    email       VARCHAR(150)    NULL,
    telefono    VARCHAR(30)     NULL,
    direccion   VARCHAR(255)    NULL,
    deleted_at  TIMESTAMP       NULL,
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_clientes_cedula (cedula)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 2. ABOGADOS
-- ----------------------------------------------------------------------------
CREATE TABLE abogados (
    id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    cedula        VARCHAR(20)     NOT NULL,
    nombre        VARCHAR(100)    NOT NULL,
    apellido      VARCHAR(100)    NOT NULL,
    email         VARCHAR(150)    NULL,
    telefono      VARCHAR(30)     NULL,
    especialidad  VARCHAR(100)    NULL,
    deleted_at    TIMESTAMP       NULL,
    created_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_abogados_cedula (cedula)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 3. CASOS
-- ----------------------------------------------------------------------------
CREATE TABLE casos (
    id                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    numero_expediente   VARCHAR(30)     NOT NULL,
    cliente_id          BIGINT UNSIGNED NOT NULL,
    fecha_inicio        DATE            NOT NULL,
    fecha_finalizacion  DATE            NULL,
    estado              ENUM('en_tramite', 'archivado', 'sentenciado', 'desistido', 'suspendido')
                                        NOT NULL DEFAULT 'en_tramite',
    descripcion         TEXT            NULL,
    deleted_at          TIMESTAMP       NULL,
    created_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_casos_numero_expediente (numero_expediente),
    KEY idx_casos_cliente_id (cliente_id),
    KEY idx_casos_estado (estado),
    CONSTRAINT fk_casos_cliente
        FOREIGN KEY (cliente_id) REFERENCES clientes (id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 4. CASO_ABOGADO (relación N:M entre casos y abogados)
-- ----------------------------------------------------------------------------
CREATE TABLE caso_abogado (
    id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    caso_id         BIGINT UNSIGNED NOT NULL,
    abogado_id      BIGINT UNSIGNED NOT NULL,
    fecha_asignacion DATE           NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_caso_abogado (caso_id, abogado_id),
    KEY idx_caso_abogado_abogado (abogado_id),
    CONSTRAINT fk_caso_abogado_caso
        FOREIGN KEY (caso_id) REFERENCES casos (id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_caso_abogado_abogado
        FOREIGN KEY (abogado_id) REFERENCES abogados (id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 5. TRIGGERS ANTI-ELIMINACIÓN
-- Impide la eliminación física de cualquier registro (requisito del negocio).
-- ----------------------------------------------------------------------------
DELIMITER $$

CREATE TRIGGER trg_clientes_no_delete
BEFORE DELETE ON clientes
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se permite eliminar registros de clientes (protección de integridad legal)';
END$$

CREATE TRIGGER trg_abogados_no_delete
BEFORE DELETE ON abogados
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se permite eliminar registros de abogados (protección de integridad legal)';
END$$

CREATE TRIGGER trg_casos_no_delete
BEFORE DELETE ON casos
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se permite eliminar registros de casos (protección de integridad legal)';
END$$

CREATE TRIGGER trg_caso_abogado_no_delete
BEFORE DELETE ON caso_abogado
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se permite eliminar registros de caso_abogado (protección de integridad legal)';
END$$

DELIMITER ;

-- ============================================================================
-- 6. DATOS DE PRUEBA
-- ============================================================================

INSERT INTO clientes (cedula, nombre, apellido, email, telefono, direccion) VALUES
    ('1012345678', 'Carlos',    'Gómez',     'carlos.gomez@mail.com',     '3101112233', 'Calle 1 # 2-3, Bogotá'),
    ('1019876543', 'María',     'López',     'maria.lopez@mail.com',      '3202223344', 'Carrera 4 # 5-6, Medellín'),
    ('1023456789', 'Andrés',    'Ramírez',   'andres.ramirez@mail.com',   '3003334455', 'Avenida 7 # 8-9, Cali'),
    ('1034567890', 'Laura',     'Torres',    'laura.torres@mail.com',     '3154445566', 'Calle 10 # 11-12, Barranquilla'),
    ('1045678901', 'Jorge',     'Martínez',  'jorge.martinez@mail.com',   '3015556677', 'Carrera 13 # 14-15, Cartagena');

INSERT INTO abogados (cedula, nombre, apellido, email, telefono, especialidad) VALUES
    ('2011112222', 'Juan',      'Pérez',     'juan.perez@bufete.com',     '3116667788', 'Derecho Civil'),
    ('2022223333', 'Ana',       'Rodríguez', 'ana.rodriguez@bufete.com',  '3127778899', 'Derecho Penal'),
    ('2033334444', 'Luis',      'Fernández', 'luis.fernandez@bufete.com', '3138889900', 'Derecho Laboral'),
    ('2044445555', 'Carolina',  'Silva',     'carolina.silva@bufete.com', '3149990011', 'Derecho Comercial');

INSERT INTO casos (numero_expediente, cliente_id, fecha_inicio, fecha_finalizacion, estado, descripcion) VALUES
    ('EXP-2024-0001', 1, '2024-01-15', NULL,              'en_tramite',  'Demanda civil por incumplimiento de contrato'),
    ('EXP-2024-0002', 1, '2024-03-02', '2024-11-20',      'archivado',   'Reclamación de deuda'),
    ('EXP-2024-0003', 2, '2024-02-10', NULL,              'en_tramite',  'Proceso penal por estafa'),
    ('EXP-2024-0004', 2, '2024-05-18', '2025-02-14',      'sentenciado', 'Accidente de tránsito'),
    ('EXP-2024-0005', 3, '2024-04-25', NULL,              'en_tramite',  'Despido injustificado'),
    ('EXP-2024-0006', 3, '2024-07-08', '2024-12-10',      'desistido',   'Disputa de propiedad'),
    ('EXP-2025-0007', 4, '2025-01-12', NULL,              'en_tramite',  'Constitución de sociedad'),
    ('EXP-2025-0008', 4, '2025-03-30', NULL,              'suspendido',  'Conflicto societario'),
    ('EXP-2025-0009', 5, '2025-02-05', '2025-10-22',      'archivado',   'Sucesión y herencia'),
    ('EXP-2025-0010', 5, '2025-06-17', NULL,              'en_tramite',  'Cobro de honorarios profesionales');

INSERT INTO caso_abogado (caso_id, abogado_id, fecha_asignacion) VALUES
    (1, 1, '2024-01-16'),
    (1, 4, '2024-01-20'),
    (2, 1, '2024-03-03'),
    (3, 2, '2024-02-11'),
    (4, 2, '2024-05-19'),
    (5, 3, '2024-04-26'),
    (6, 3, '2024-07-09'),
    (7, 4, '2025-01-13'),
    (8, 4, '2025-03-31'),
    (8, 1, '2025-04-05'),
    (9, 1, '2025-02-06'),
    (10, 3, '2025-06-18');

-- ============================================================================
-- 7. CONSULTAS SOLICITADAS EN LA PRUEBA
-- ============================================================================

-- 7.1 Casos asociados a un cliente según su cédula
SELECT
    c.numero_expediente,
    c.estado,
    c.fecha_inicio,
    c.fecha_finalizacion,
    cl.cedula,
    CONCAT(cl.nombre, ' ', cl.apellido) AS cliente
FROM casos c
INNER JOIN clientes cl ON cl.id = c.cliente_id
WHERE cl.cedula = '1012345678'
ORDER BY c.numero_expediente;

-- 7.2 Todos los casos en orden ascendente
SELECT *
FROM casos
ORDER BY numero_expediente ASC;

-- 7.3 Los 5 (cinco) primeros registros
SELECT *
FROM casos
ORDER BY id ASC
LIMIT 5;