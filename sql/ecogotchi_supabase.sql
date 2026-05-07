-- ============================================================
-- EcoGotchi — Schema PostgreSQL (compatible con Supabase)
-- Convertido desde MySQL/MariaDB
-- ============================================================

-- Eliminar tablas si existen (en orden inverso por dependencias)
DROP TABLE IF EXISTS compras CASCADE;
DROP TABLE IF EXISTS misiones_completadas CASCADE;
DROP TABLE IF EXISTS mascotas_historial CASCADE;
DROP TABLE IF EXISTS mascotas CASCADE;
DROP TABLE IF EXISTS entorno CASCADE;
DROP TABLE IF EXISTS tienda_acciones CASCADE;
DROP TABLE IF EXISTS misiones CASCADE;
DROP TABLE IF EXISTS usuarios CASCADE;

-- ============================================================
-- TABLA: usuarios
-- ============================================================
CREATE TABLE usuarios (
    id               SERIAL PRIMARY KEY,
    nombre           VARCHAR(50)  NOT NULL,
    email            VARCHAR(100) NOT NULL UNIQUE,
    password         VARCHAR(255) NOT NULL,
    fecha_registro   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    monedas_verdes   INTEGER      NOT NULL DEFAULT 0
);

-- ============================================================
-- TABLA: mascotas
-- ============================================================
CREATE TABLE mascotas (
    id                          SERIAL PRIMARY KEY,
    id_usuario                  INTEGER REFERENCES usuarios(id) ON DELETE CASCADE,
    nombre                      VARCHAR(50),
    tipo                        VARCHAR(50),
    color                       VARCHAR(30),
    hambre                      INTEGER   DEFAULT 80,
    sueno                       INTEGER   DEFAULT 80,
    diversion                   INTEGER   DEFAULT 80,
    higiene                     INTEGER   DEFAULT 80,
    salud                       INTEGER   DEFAULT 100,
    fecha_ultima_actualizacion  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    basura                      SMALLINT  NOT NULL DEFAULT 0
);

-- ============================================================
-- TABLA: mascotas_historial
-- ============================================================
CREATE TABLE mascotas_historial (
    id                          SERIAL PRIMARY KEY,
    id_usuario                  INTEGER NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    nombre                      VARCHAR(50),
    tipo                        VARCHAR(50),
    color                       VARCHAR(30),
    hambre                      INTEGER,
    sueno                       INTEGER,
    diversion                   INTEGER,
    higiene                     INTEGER,
    salud                       INTEGER,
    basura                      SMALLINT  DEFAULT 0,
    fecha_ultima_actualizacion  TIMESTAMP,
    fecha_fin                   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    motivo_fin                  VARCHAR(50) DEFAULT 'muerte'
);

-- ============================================================
-- TABLA: entorno
-- ============================================================
CREATE TABLE entorno (
    id                          SERIAL PRIMARY KEY,
    id_usuario                  INTEGER REFERENCES usuarios(id) ON DELETE CASCADE,
    nivel_ecologico             INTEGER     DEFAULT 50,
    estado_entorno              VARCHAR(30) DEFAULT 'normal',
    fecha_ultima_actualizacion  TIMESTAMP   DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLA: misiones
-- ============================================================
CREATE TABLE misiones (
    id             SERIAL PRIMARY KEY,
    titulo         VARCHAR(100),
    descripcion    TEXT,
    recompensa     INTEGER,
    puntos_monedas INTEGER NOT NULL DEFAULT 5
);

-- ============================================================
-- TABLA: misiones_completadas
-- ============================================================
CREATE TABLE misiones_completadas (
    id         SERIAL PRIMARY KEY,
    id_usuario INTEGER REFERENCES usuarios(id) ON DELETE CASCADE,
    id_mision  INTEGER REFERENCES misiones(id),
    fecha      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLA: tienda_acciones
-- ============================================================
CREATE TABLE tienda_acciones (
    id           SERIAL PRIMARY KEY,
    titulo       VARCHAR(100) NOT NULL,
    descripcion  TEXT,
    icono        VARCHAR(20),
    coste        INTEGER NOT NULL,
    impacto_real TEXT
);

-- ============================================================
-- TABLA: compras
-- ============================================================
CREATE TABLE compras (
    id         SERIAL PRIMARY KEY,
    id_usuario INTEGER NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    id_accion  INTEGER NOT NULL REFERENCES tienda_acciones(id),
    fecha      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- DATOS: misiones (20 misiones ecológicas)
-- ============================================================
INSERT INTO misiones (titulo, descripcion, recompensa, puntos_monedas) VALUES
('Reciclar plástico',      'Hoy reciclaste una botella de plástico',                       10, 10),
('Usar transporte público', 'Evita el coche hoy',                                           15, 20),
('Ahorrar agua',           'Reduce el tiempo en la ducha',                                  10, 10),
('Apagar luces',           'Apaga luces innecesarias',                                       5,  5),
('Reutilizar bolsa',       'Usa una bolsa reutilizable al hacer compras',                   10, 10),
('Separar residuos',       'Separa papel, plástico y orgánico correctamente',               10, 15),
('Desconectar cargadores', 'Desconecta cargadores que no estés usando',                      5,  5),
('Recoger basura',         'Recoge al menos un residuo que encuentres en la calle',         15, 20),
('Usar botella reutilizable','Evita comprar botellas de plástico hoy',                      10, 10),
('Plantar una planta',     'Planta una semilla o cuida una planta',                         20, 25),
('Comer local',            'Consume alimentos producidos localmente',                        10, 10),
('Reducir carne',          'Evita comer carne hoy para reducir huella ecológica',           15, 15),
('Apagar dispositivos',    'Apaga dispositivos electrónicos que no uses',                    5,  5),
('Ir caminando',           'Camina en lugar de usar transporte contaminante',               15, 15),
('Usar luz natural',       'Aprovecha la luz del sol en lugar de encender lámparas',         5,  5),
('Ducha corta',            'Dúchate en menos de 5 minutos',                                 10, 10),
('Reutilizar papel',       'Usa las dos caras del papel antes de tirarlo',                   5,  5),
('Comprar a granel',       'Compra productos sin envases innecesarios',                     10, 10),
('Regar plantas',          'Cuida las plantas o árboles cercanos',                           5,  5),
('Compartir transporte',   'Comparte coche o transporte con alguien',                       15, 15);

-- ============================================================
-- DATOS: tienda_acciones (13 acciones de impacto real)
-- ============================================================
INSERT INTO tienda_acciones (titulo, descripcion, icono, coste, impacto_real) VALUES
('Punto de Reciclaje Barrio',  'Apoyas la instalación de un punto de reciclaje en tu vecindario para gestionar mejor los residuos.',  '🌳', 25,  'Reduce hasta 300 kg de basura mezclada al año.'),
('Plantar un Roble Centenario','Contribuyes a la reforestación plantando un árbol que absorberá CO2 durante décadas.',                 '🌲', 40,  'Absorbe hasta 20kg de CO2 al año.'),
('Apoyar Bici-Escuela',        'Fomenta el transporte sostenible financiando clases de ciclismo urbano para jóvenes.',                '🚴', 30,  'Ahorra aprox. 150kg de CO2 por alumno al año.'),
('Reforestar zona quemada',    'Ayudas a recuperar bosque perdido por incendios.',                                                     '🔥', 70,  'Por cada hectárea reforestada se fijan 50 toneladas de CO2.'),
('Limpieza de Playas',         'Contribuyes a la recogida manual de plásticos y residuos en nuestras costas.',                        '🌊', 45,  'Retira 15kg de plásticos del mar.'),
('Energía Solar Escolar',      'Instalación de paneles fotovoltaicos en colegios públicos para reducir emisiones.',                   '☀️', 80,  'Ahorra 1MWh de energía contaminante.'),
('Santuario de Abejas',        'Creación de colmenas urbanas y siembra de flores silvestres para polinizadores.',                     '🐝', 35,  'Ayuda a polinizar 5.000 flores nuevas.'),
('Reforestar el Amazonas',     'Siembra de árboles autóctonos en zonas protegidas de la selva amazónica.',                           '🌴', 100, 'Captura 1 tonelada de CO2 en 10 años.'),
('Pozos de Agua Solar',        'Construcción de pozos con bombeo solar en zonas con escasez de agua.',                                '💧', 65,  'Suministra agua potable a 5 familias.'),
('Huerto Comunitario',         'Creación de espacios de cultivo urbano sostenible para vecinos del barrio.',                          '🍅', 40,  'Produce 30kg de vegetales frescos locales.');
