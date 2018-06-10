------------------------------
-- Archivo de base de datos --
------------------------------

-- TABLAS --

DROP TABLE IF EXISTS config CASCADE;

CREATE TABLE config
(
    id                      BIGSERIAL    PRIMARY KEY
  , mensaje_twitter         VARCHAR(120) NOT NULL
  , mensaje_unete_twitter   VARCHAR(120) NOT NULL
  , mensaje_whatsapp        VARCHAR(255) NOT NULL
  , mensaje_unete_whatsapp  VARCHAR(255) NOT NULL
  , usuario_twitch          VARCHAR(255) NOT NULL
  , coleccion_twitch        VARCHAR(64)
  , accion                  VARCHAR(1)   CHECK (accion = 'd' OR accion = 'p')
);

DROP TABLE IF EXISTS ficheros_subidos CASCADE;

CREATE TABLE ficheros_subidos
(
    id        BIGSERIAL PRIMARY KEY
  , contenido BYTEA     NOT NULL
);

DROP TABLE IF EXISTS mejores_partidas CASCADE;

CREATE TABLE mejores_partidas
(
    id           BIGSERIAL    PRIMARY KEY
  , video_twitch VARCHAR(32)  NOT NULL UNIQUE
  , orden        NUMERIC(1)   NOT NULL UNIQUE
                              CONSTRAINT ck_orden_mejores_partidas_positivo
                              CHECK (orden >= 0)
);

DROP TABLE IF EXISTS noticias CASCADE;

CREATE TABLE noticias
(
    id         BIGSERIAL    PRIMARY KEY
  , titular    VARCHAR(64)  NOT NULL
  , contenido  TEXT         NOT NULL
                            CONSTRAINT ck_contenido_limite
                            CHECK (char_length(contenido) < 2000)
  , created_at TIMESTAMP(0) NOT NULL
                            DEFAULT current_timestamp
  , updated_at TIMESTAMP(0) NOT NULL
  , tweet      VARCHAR(18)
);

DROP TABLE IF EXISTS evento_etiquetas CASCADE;

CREATE TABLE evento_etiquetas
(
    id     BIGSERIAL    PRIMARY KEY
  , nombre VARCHAR(32)  NOT NULL UNIQUE
  , color  VARCHAR(6)   UNIQUE
);

DROP TABLE IF EXISTS permisos CASCADE;

CREATE TABLE permisos
(
    id          BIGSERIAL     PRIMARY KEY
  , nombre      VARCHAR(32)   NOT NULL UNIQUE
  , descripcion VARCHAR(255)
);

DROP TABLE IF EXISTS nacionalidades CASCADE;

CREATE TABLE nacionalidades
(
    id      BIGSERIAL   PRIMARY KEY
  , nombre  VARCHAR(32) NOT NULL
  , pais    VARCHAR(32) NOT NULL
);

DROP TABLE IF EXISTS ligas CASCADE;

CREATE TABLE ligas
(
    id     BIGSERIAL    PRIMARY KEY
  , nombre VARCHAR(255) NOT NULL UNIQUE
  , icono  NUMERIC(2)   NOT NULL
);

DROP TABLE IF EXISTS cofres CASCADE;

CREATE TABLE cofres
(
    id     BIGSERIAL    PRIMARY KEY
  , nombre VARCHAR(255) NOT NULL UNIQUE
  , icono  NUMERIC(2)   NOT NULL UNIQUE
);

DROP TABLE IF EXISTS torneo_etiquetas CASCADE;

CREATE TABLE torneo_etiquetas
(
    id     BIGSERIAL    PRIMARY KEY
  , nombre VARCHAR(32)  NOT NULL UNIQUE
  , color  VARCHAR(6)   UNIQUE
);

DROP TABLE IF EXISTS torneos CASCADE;

CREATE TABLE torneos
(
    id                 BIGSERIAL    PRIMARY KEY
  , tag                VARCHAR(8)   NOT NULL UNIQUE
  , torneo_etiqueta_id BIGINT       NOT NULL
                                    REFERENCES torneo_etiquetas (id)
                                    ON DELETE NO ACTION
                                    ON UPDATE CASCADE
  , nombre             VARCHAR(255) NOT NULL
  , password           VARCHAR(255)
  , descripcion        TEXT
  , capacidad          NUMERIC(4)   NOT NULL
                                    CONSTRAINT ck_capacidad_positiva
                                    CHECK (capacidad >= 0)
  , max_capacidad      NUMERIC(4)   NOT NULL
                                    CONSTRAINT ck_max_capacidad_positiva
                                    CHECK (max_capacidad >= 0)
  , fecha_comienzo     TIMESTAMP(0) NOT NULL
  , duracion           INTEGER      NOT NULL
                                    CONSTRAINT ck_duracion_positiva
                                    CHECK (duracion >= 0)
  , estado             BOOLEAN      DEFAULT TRUE -- ABIERTO O CERRADO
);

CREATE INDEX idx_torneos_nombre                ON torneos (nombre);
CREATE INDEX idx_torneos_capacidad             ON torneos (capacidad);
CREATE INDEX idx_torneos_max_capacidad         ON torneos (max_capacidad);
CREATE INDEX idx_torneos_fecha_comienzo        ON torneos (fecha_comienzo DESC);
CREATE INDEX idx_torneos_duracion              ON torneos (duracion);
CREATE INDEX idx_torneos_estado                ON torneos (estado);
CREATE INDEX idx_torneos_fecha_comienzo_estado ON torneos (fecha_comienzo DESC, estado);

DROP TABLE IF EXISTS batalla_etiquetas CASCADE;

CREATE TABLE batalla_etiquetas
(
    id     BIGSERIAL   PRIMARY KEY
  , nombre VARCHAR(32) NOT NULL UNIQUE
  , color  VARCHAR(6)  UNIQUE
);

DROP TABLE IF EXISTS batalla_tipos CASCADE;

CREATE TABLE batalla_tipos
(
    id     BIGSERIAL   PRIMARY KEY
  , nombre VARCHAR(32) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS rarezas CASCADE;

CREATE TABLE rarezas
(
    id     BIGSERIAL   PRIMARY KEY
  , nombre VARCHAR(32) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS eventos CASCADE;

CREATE TABLE eventos
(
    id                 BIGSERIAL PRIMARY KEY
  , evento_etiqueta_id BIGINT    NOT NULL
                                 REFERENCES evento_etiquetas (id)
                                 ON DELETE NO ACTION
                                 ON UPDATE CASCADE
  , descripcion        TEXT      CONSTRAINT ck_descripcion_limite
                                 CHECK (char_length(descripcion) < 2000)
);

CREATE INDEX idx_eventos_evento_etiqueta_id  ON eventos (evento_etiqueta_id);

DROP TABLE IF EXISTS roles CASCADE;

CREATE TABLE roles
(
    id          BIGSERIAL    PRIMARY KEY
  , nombre      VARCHAR(32)  NOT NULL UNIQUE
);

DROP TABLE IF EXISTS roles_permisos CASCADE;

CREATE TABLE roles_permisos
(
    id          BIGSERIAL    PRIMARY KEY
  , rol_id      BIGINT       NOT NULL
                             REFERENCES roles (id)
                             ON DELETE NO ACTION
                             ON UPDATE CASCADE
  , permiso_id  BIGINT       NOT NULL
                             REFERENCES permisos (id)
                             ON DELETE NO ACTION
                             ON UPDATE CASCADE
  , CONSTRAINT uq_roles_permisos UNIQUE (rol_id, permiso_id)
);

DROP TABLE IF EXISTS visibilidad_torneos CASCADE;

CREATE TABLE visibilidad_torneos
(
    id        BIGSERIAL  PRIMARY KEY
  , torneo_id BIGINT     NOT NULL
                         REFERENCES torneos (id)
                         ON DELETE NO ACTION
                         ON UPDATE CASCADE
  , rol_id    BIGINT     NOT NULL
                         REFERENCES roles (id)
                         ON DELETE NO ACTION
                         ON UPDATE CASCADE
  , CONSTRAINT uq_visibilidad_torneos_torneo_id_rol_id
    UNIQUE (torneo_id, rol_id)
);

DROP TABLE IF EXISTS visibilidad_eventos CASCADE;

CREATE TABLE visibilidad_eventos
(
    id        BIGSERIAL   PRIMARY KEY
  , evento_id BIGINT      NOT NULL
                          REFERENCES eventos (id)
                          ON DELETE CASCADE
                          ON UPDATE CASCADE
  , rol_id    BIGINT      NOT NULL
                          REFERENCES roles (id)
                          ON DELETE NO ACTION
                          ON UPDATE CASCADE
  , CONSTRAINT uq_visibilidad_eventos_evento_id_rol_id
    UNIQUE (evento_id, rol_id)
);

DROP TABLE IF EXISTS rarezas CASCADE;

CREATE TABLE rarezas
(
    id     BIGSERIAL    PRIMARY KEY
  , nombre VARCHAR(255) NOT NULL UNIQUE
);
/*
DROP TABLE IF EXISTS carta_tipos CASCADE;

CREATE TABLE carta_tipos
(
    id     BIGSERIAL    PRIMARY KEY
  , nombre VARCHAR(255) NOT NULL UNIQUE
);
*/
/*
DROP TABLE IF EXISTS cartas CASCADE;

CREATE TABLE cartas
(
    id            BIGSERIAL    PRIMARY KEY
  , nombre        VARCHAR(255) NOT NULL UNIQUE
  , rareza_id     BIGINT       REFERENCES rarezas (id)
                               ON DELETE NO ACTION
                               ON UPDATE CASCADE
  , icono         NUMERIC(4)   NOT NULL UNIQUE
  , carta_tipo_id BIGINT       REFERENCES cartas (id)
                               ON DELETE NO ACTION
                               ON UPDATE CASCADE
);
*/
--CREATE INDEX idx_cartas_rareza_id     ON cartas (rareza_id);
--CREATE INDEX idx_cartas_carta_tipo_id ON cartas (carta_tipo_id);

DROP TABLE IF EXISTS zonas_horarias CASCADE;

CREATE TABLE zonas_horarias
(
      id    BIGSERIAL   PRIMARY KEY
    , zona  NUMERIC(2)  NOT NULL UNIQUE
                                 CONSTRAINT ck_zona_valido
                                 CHECK (zona >= -11 AND zona <= 12)
    , lugar TEXT
);

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios
(
    id               BIGSERIAL    PRIMARY KEY
  , expulsado        TIMESTAMP(0) -- HASTA FECHA
  --, deleted_at       TIMESTAMP(0) -- SOFT DELETE
  , nombre           VARCHAR(255) NOT NULL UNIQUE
  , password         VARCHAR(255) NOT NULL
  , correo           VARCHAR(255) NOT NULL UNIQUE
  , nacionalidad_id  BIGINT       NOT NULL
                                  REFERENCES nacionalidades (id)
                                  ON DELETE NO ACTION
                                  ON UPDATE CASCADE
  , jugador_id       BIGINT       REFERENCES jugadores (id)
                                  ON DELETE NO ACTION
                                  ON UPDATE CASCADE
  , zona_horaria_id BIGINT        NOT NULL
                                  REFERENCES zonas_horarias (id)
  , access_token     VARCHAR(255)
  , auth_key         VARCHAR(255)
  , activo           BOOLEAN      DEFAULT FALSE
  , verificado       VARCHAR(255)
  , created_at       TIMESTAMP(0) NOT NULL
                                  DEFAULT current_timestamp
  , updated_at       TIMESTAMP(0)
);

DROP TABLE IF EXISTS usuarios_roles CASCADE;

CREATE TABLE usuarios_roles
(
      id         BIGSERIAL   PRIMARY KEY
    , usuario_id BIGINT      NOT NULL
                             REFERENCES usuarios (id)
                             ON DELETE CASCADE
                             ON UPDATE CASCADE
    , rol_id     BIGINT      NOT NULL
                             REFERENCES roles (id)
                             ON DELETE NO ACTION
                             ON UPDATE CASCADE
    , CONSTRAINT uq_usuarios_roles UNIQUE (usuario_id, rol_id)
);

DROP TABLE IF EXISTS jugadores CASCADE;

CREATE TABLE jugadores
(
    id                      BIGSERIAL    PRIMARY KEY
  , tag                     VARCHAR(16)  NOT NULL UNIQUE
  , clan_tag                VARCHAR(16)
  , clan_rol                VARCHAR(16)
  , nombre                  VARCHAR(255) NOT NULL
  , nivel                   NUMERIC(5)   NOT NULL
                                         CONSTRAINT ck_nivel_positivo
                                         CHECK (nivel >= 0)
  , copas                   NUMERIC(16)  NOT NULL
                                         CONSTRAINT ck_copas_positivas
                                         CHECK (copas >= 0)
  , max_copas               NUMERIC(16)  NOT NULL
                                         CONSTRAINT ck_max_copas_positivas
                                         CHECK (max_copas >= 0)
  , partidas_totales        INTEGER      NOT NULL
                                         CONSTRAINT ck_partidas_totales_positivo
                                         CHECK (partidas_totales >= 0)
  , victorias               INTEGER      NOT NULL
                                         CONSTRAINT ck_victorias_positivas
                                         CHECK (victorias >= 0)
  , derrotas                INTEGER      NOT NULL
                                         CONSTRAINT ck_derrotas_positivas
                                         CHECK (derrotas >= 0)
  , empates                 INTEGER      NOT NULL
                                         CONSTRAINT ck_empates_positivos
                                         CHECK (empates >= 0)
  , victorias_tres_coronas  INTEGER      NOT NULL
                                         DEFAULT 0
                                         CONSTRAINT ck_victorias_tres_coronas_positivas
                                         CHECK (victorias_tres_coronas >= 0)
  , donaciones_totales      INTEGER      CONSTRAINT ck_donaciones_totales_positivas
                                         CHECK (donaciones_totales >= 0)
  , donaciones_equipo       INTEGER      CONSTRAINT ck_donaciones_equipo_positivas
                                         CHECK (donaciones_equipo >= 0)
  , cartas_descubiertas     NUMERIC(10)  NOT NULL
                                         CONSTRAINT ck_cartas_descubiertas_positivas
                                         CHECK (cartas_descubiertas >= 0)
/*
  , carta_favorita          INTEGER      NOT NULL
                                         CONSTRAINT ck_carta_favorita_positiva
                                         CHECK (carta_favorita >= 0)
*/
/*
  , carta_favorita          BIGINT       REFERENCES cartas (id)
                                         ON DELETE NO ACTION
                                         ON UPDATE CASCADE
*/
/*
  , equipo_partidas_jugadas INTEGER      CONSTRAINT ck_equipo_partidas_jugadas_positivas
                                         CHECK (equipo_partidas_jugadas >= 0)
  , equipo_cartas_ganadas   NUMERIC(4)   DEFAULT 0
                                         CONSTRAINT ck_equipo_cartas_ganadas_positivas
                                         CHECK (equipo_cartas_ganadas >= 0)
*/
  , desafio_max_victorias   INTEGER      CONSTRAINT ck_desafio_max_victorias_positivas
                                         CHECK (desafio_max_victorias >= 0)
  , desafio_cartas_ganadas  NUMERIC(10)  CONSTRAINT ck_desafio_cartas_ganadas_positivas
                                         CHECK (desafio_cartas_ganadas >= 0)
  , liga_id                 BIGINT       NOT NULL REFERENCES ligas (id)
                                         ON DELETE NO ACTION
                                         ON UPDATE CASCADE
);

CREATE INDEX idx_jugadores_nivel                   ON jugadores (nivel);
CREATE INDEX idx_jugadores_copas                   ON jugadores (copas);
CREATE INDEX idx_jugadores_max_copas               ON jugadores (max_copas);
CREATE INDEX idx_jugadores_partidas_totales        ON jugadores (partidas_totales);
CREATE INDEX idx_jugadores_victorias               ON jugadores (victorias);
CREATE INDEX idx_jugadores_derrotas                ON jugadores (derrotas);
CREATE INDEX idx_jugadores_empates                 ON jugadores (empates);
CREATE INDEX idx_jugadores_victorias_tres_coronas  ON jugadores (victorias_tres_coronas);
CREATE INDEX idx_jugadores_donaciones_totales      ON jugadores (donaciones_totales);
CREATE INDEX idx_jugadores_donaciones_equipo       ON jugadores (donaciones_equipo);
CREATE INDEX idx_jugadores_cartas_descubiertas     ON jugadores (cartas_descubiertas);
--CREATE INDEX idx_jugadores_carta_favorita          ON jugadores (carta_favorita);
--CREATE INDEX idx_jugadores_equipo_partidas_jugadas ON jugadores (equipo_partidas_jugadas);
--CREATE INDEX idx_jugadores_equipo_cartas_ganadas   ON jugadores (equipo_cartas_ganadas);
CREATE INDEX idx_jugadores_desafio_max_victorias   ON jugadores (desafio_max_victorias);
CREATE INDEX idx_jugadores_desafio_cartas_ganadas  ON jugadores (desafio_cartas_ganadas);
CREATE INDEX idx_jugadores_liga_id                 ON jugadores (liga_id);

DROP TABLE IF EXISTS evento_integrantes CASCADE;

CREATE TABLE evento_integrantes
(
    id         BIGSERIAL   PRIMARY KEY
  , evento_id  BIGINT      NOT NULL
                           REFERENCES eventos (id)
                           ON DELETE CASCADE
                           ON UPDATE CASCADE
  , jugador_id BIGINT      NOT NULL
                           REFERENCES jugadores (id)
                           ON DELETE NO ACTION
                           ON UPDATE CASCADE
  , CONSTRAINT uq_evento_integrantes_evento_id_jugador_id
    UNIQUE (evento_id, jugador_id)
);

DROP TABLE IF EXISTS torneo_jugadores CASCADE;

CREATE TABLE torneo_jugadores
(
    id         BIGSERIAL   PRIMARY KEY
  , torneo_id  BIGINT      NOT NULL
                           REFERENCES torneos (id)
                           ON DELETE CASCADE
                           ON UPDATE CASCADE
  , jugador_id  BIGINT     NOT NULL
                           REFERENCES jugadores (id)
                           ON DELETE NO ACTION
                           ON UPDATE CASCADE
  , puntos     NUMERIC(6)  NOT NULL
                           DEFAULT 0
                           CONSTRAINT ck_puntos_positivos
                           CHECK (puntos >= 0)
  , CONSTRAINT uq_torneo_jugadores_torneo_id_jugador_id
    UNIQUE (torneo_id, jugador_id)
);

CREATE INDEX idx_torneo_jugadores_puntos ON torneo_jugadores (puntos);

DROP TABLE IF EXISTS oponentes CASCADE;

-- Valores nulos a aquellos oponentes que son jugadores del propio equipo,
-- solo se guardaria el tag
CREATE TABLE oponentes
(
    id          BIGSERIAL    PRIMARY KEY
  , tag         VARCHAR(9)   NOT NULL UNIQUE
  , nombre      VARCHAR(255)
  , tag_torneo    VARCHAR(9)
  , nombre_torneo VARCHAR(255)
);

CREATE INDEX idx_oponentes_tag_torneo    ON oponentes (tag_torneo);
CREATE INDEX idx_oponentes_nombre_torneo ON oponentes (nombre_torneo);

DROP TABLE IF EXISTS batallas CASCADE;

CREATE TABLE batallas
(
    id                              BIGSERIAL    PRIMARY KEY
  , batalla_etiqueta_id             BIGINT       NOT NULL
                                                 REFERENCES batalla_etiquetas (id)
                                                 ON DELETE NO ACTION
                                                 ON UPDATE CASCADE
  , batalla_tipo_id                 BIGINT       NOT NULL
                                                 REFERENCES batalla_tipos (id)
                                                 ON DELETE NO ACTION
                                                 ON UPDATE CASCADE
  , jugador_id                      BIGINT       NOT NULL
                                                 REFERENCES jugadores (id)
                                                 ON DELETE NO ACTION
                                                 ON UPDATE CASCADE
  , oponente_id                     BIGINT       NOT NULL
                                                 REFERENCES oponentes (id)
                                                 ON DELETE NO ACTION
                                                 ON UPDATE CASCADE
  , tiempo                          TIMESTAMP(0) NOT NULL
  , coronas_jugador                 NUMERIC(1)   NOT NULL
                                                 DEFAULT 0
                                                 CONSTRAINT ck_coronas_jugador_validas
                                                 CHECK (coronas_jugador >= 0 AND coronas_jugador <= 3)
  , coronas_oponente                NUMERIC(1)   NOT NULL
                                                 DEFAULT 0
                                                 CONSTRAINT ck_coronas_oponente_validas
                                                 CHECK (coronas_oponente >= 0 AND coronas_oponente <= 3)
  , copas_comienzo_partida_jugador  NUMERIC(8)   NOT NULL
                                                 CONSTRAINT ck_copas_comienzo_partida_jugador_positivas
                                                 CHECK (copas_comienzo_partida_jugador >= 0)
  , copas_cambio_jugador            NUMERIC(6)   NOT NULL
                                                 CONSTRAINT ck_copas_cambio_jugador_positivas
                                                 CHECK (copas_cambio_jugador >= 0)
  , copas_comienzo_partida_oponente NUMERIC(8)   NOT NULL
                                                 CONSTRAINT ck_copas_comienzo_partida_oponente_positivas
                                                 CHECK (copas_comienzo_partida_oponente >= 0)
  , copas_cambio_oponente           NUMERIC(6)   NOT NULL
                                                 CONSTRAINT ck_copas_cambio_oponente_positivas
                                                 CHECK (copas_cambio_oponente >= 0)
  , video_twitch                    VARCHAR(32)
);

CREATE INDEX idx_batallas_batalla_etiqueta_id          ON batallas (batalla_etiqueta_id);
CREATE INDEX idx_batallas_batalla_tipo_id              ON batallas (batalla_tipo_id);
CREATE INDEX idx_batallas_jugador_id                   ON batallas (jugador_id);
CREATE INDEX idx_batallas_oponente_id                  ON batallas (oponente_id);
CREATE INDEX idx_batallas_tiempo                       ON batallas (tiempo);
CREATE INDEX idx_batallas_coronas_jugador              ON batallas (coronas_jugador);
CREATE INDEX idx_batallas_coronas_oponente             ON batallas (coronas_oponente);
CREATE INDEX idx_batallas_video_twitch                 ON batallas (video_twitch);
CREATE INDEX idx_batallas_jugador_id_oponente_id       ON batallas (jugador_id, oponente_id);
CREATE INDEX idx_batallas_jugador_id_coronas_jugador   ON batallas (jugador_id, coronas_jugador);
CREATE INDEX idx_batallas_oponente_id_coronas_oponente ON batallas (oponente_id, coronas_oponente);

DROP TABLE IF EXISTS torneo_batallas CASCADE;

CREATE TABLE torneo_batallas
(
    id         BIGSERIAL    PRIMARY KEY
  , torneo_id    BIGINT       NOT NULL
                            REFERENCES torneos (id)
                            ON DELETE NO ACTION
                            ON UPDATE CASCADE
  , batalla_id BIGINT       NOT NULL
                            REFERENCES batallas (id)
                            ON DELETE NO ACTION
                            ON UPDATE CASCADE
  , CONSTRAINT uq_torneo_batallas_torneo_id_batalla_id
    UNIQUE (torneo_id, batalla_id)
);

DROP TABLE IF EXISTS cofres_ciclos CASCADE;

CREATE TABLE cofres_ciclos
(
    id           BIGSERIAL    PRIMARY KEY
  , jugador_id   BIGINT       NOT NULL UNIQUE
                              REFERENCES jugadores (id)
                              ON DELETE NO ACTION
                              ON UPDATE CASCADE
  , cofre_id     BIGINT       NOT NULL
                              REFERENCES cofres (id)
                              ON DELETE NO ACTION
                              ON UPDATE CASCADE
  , orden        NUMERIC(1)   NOT NULL
  , cofre_ultimo BOOLEAN      NOT NULL
                              DEFAULT FALSE
);

CREATE INDEX idx_cofres_ciclos_orden        ON cofres_ciclos (orden);
CREATE INDEX idx_cofres_ciclos_cofre_ultimo ON cofres_ciclos (cofre_ultimo);

DROP TABLE IF EXISTS clanes CASCADE;

CREATE TABLE clanes
(
    id                BIGSERIAL    PRIMARY KEY
  , nombre            VARCHAR(255) NOT NULL
  , tag               VARCHAR(16)  NOT NULL UNIQUE
  , descripcion       TEXT
  , copas             NUMERIC(8)   NOT NULL
  , copas_requeridas  NUMERIC(8)   NOT NULL
  , donaciones_semana INTEGER
  , numero_miembros   INTEGER      NOT NULL
);

DROP TABLE IF EXISTS solicitudes_lucha CASCADE;

CREATE TABLE solicitudes_lucha
(
    id               BIGSERIAL    PRIMARY KEY
  , tag              VARCHAR(16)  NOT NULL UNIQUE
  , nacionalidad_id  BIGINT       NOT NULL REFERENCES nacionalidades (id)
                                  ON DELETE NO ACTION
                                  ON UPDATE CASCADE
  , correo           VARCHAR(255) NOT NULL UNIQUE
  , aceptada         BOOLEAN      NOT NULL DEFAULT FALSE
  , clan_id          BIGINT       NOT NULL REFERENCES clanes (id)
                                  ON DELETE NO ACTION
                                  ON UPDATE CASCADE

);

DROP TABLE IF EXISTS config_equipo CASCADE;

CREATE TABLE config_equipo
(
    id                BIGSERIAL    PRIMARY KEY
  , nombre            VARCHAR(255) NOT NULL
  , tag               VARCHAR(16)  NOT NULL UNIQUE
  , descripcion       TEXT
  , copas             NUMERIC(8)   NOT NULL
  , copas_requeridas  NUMERIC(8)   NOT NULL
  , donaciones_semana INTEGER
  , numero_miembros   INTEGER      NOT NULL
  /*
  , estado_cofre      BOOLEAN      NOT NULL
                                   DEFAULT FALSE
  */
);

DROP TABLE IF EXISTS config_tiempo_actualizado;
CREATE TABLE config_tiempo_actualizado
(
      id          BIGSERIAL    PRIMARY KEY
    , subrutaweb  VARCHAR(255) NOT NULL UNIQUE
    , created_at  TIMESTAMP(0) NOT NULL DEFAULT current_timestamp
);

DROP TABLE IF EXISTS directo CASCADE;

CREATE TABLE directo
(
    id                BIGSERIAL    PRIMARY KEY
  , titulo            VARCHAR(24)  NOT NULL
  , subtitulo         VARCHAR(64)
  , mensaje_twitter   TEXT         NOT NULL
  , mensaje_whatsapp  VARCHAR(120) NOT NULL
  , marcador_propio   NUMERIC(1)   NOT NULL DEFAULT 0 CHECK (marcador_propio   >= 0 AND marcador_propio   <= 3)
  , marcador_oponente NUMERIC(1)   NOT NULL DEFAULT 0 CHECK (marcador_oponente >= 0 AND marcador_oponente <= 3)
  , oponente_tag      VARCHAR(16)  NOT NULL
  , clan_id           BIGINT       NOT NULL REFERENCES clanes (id)
                                   ON DELETE NO ACTION
                                   ON UPDATE CASCADE
  , logo              TEXT         NOT NULL
);

-- TRIGGERS


-- DATOS INICIALES
CREATE EXTENSION pgcrypto;

INSERT INTO nacionalidades (nombre, pais)
VALUES ('Venezolana','Venezuela'),
('Afgana','Afganistán'),
('Albanesa','Albania'),
('Alemana','Alemania'),
('Alto volteña','Alto volta'),
('Andorrana','Andorra'),
('Angoleña','Angola'),
('Argelina','Argelia'),
('Argentina','Argentina'),
('Australiana','Australia'),
('Austriaca','Austria'),
('Bahamesa','Bahamas'),
('Bahreina','Bahrein'),
('Bangladesha','Bangladesh'),
('Barbadesa','Barbados'),
('Belga','Belgica'),
('Beliceña','Belice'),
('Bermudesa','Bermudas'),
('Birmana','Birmania'),
('Boliviana','Bolivia'),
('Botswanesa','Botswana'),
('Brasileña','Brasil'),
('Bulgara','Bulgaria'),
('Burundesa','Burundi'),
('Butana','Butan'),
('Camboyana','Khemer Rep de Camboya '),
('Camerunesa','Camerun'),
('Canadiense','Canada'),
('Centroafricana','Rep Centroafricana'),
('Chadeña','Chad'),
('Checoslovaca','Rep. Checa'),
('Chilena','Chile'),
('China','China'),
('China','Taiwan'),
('Chipriota','Chipre'),
('Colombiana','Colombia'),
('Congoleña','Congo'),
('Costarricense','Costa Rica'),
('Cubana','Cuba'),
('Dahoneya','Dahoney'),
('Danes','Dinamarca'),
('Dominicana','Rep. Dominicana'),
('Ecuatoriana','Ecuador'),
('Egipcia','Egipto'),
('Emirata','Emiratos Arabes Udo.'),
('Escosesa','Escocia'),
('Eslovaca','Rep. Eslovaca'),
('Española','España'),
('Estona','Estonia'),
('Etiope','Etiopia'),
('Fijena','Fiji'),
('Filipina','Filipinas'),
('Finlandesa','Finlandia'),
('Francesa','Francia'),
('Gabiana','Gambia'),
('Gabona','Gabon'),
('Galesa','Gales'),
('Ghanesa','Ghana'),
('Granadeña','Granada'),
('Griega','Grecia'),
('Guatemalteca','Guatemala'),
('Guinesa Ecuatoriana','Guinea Ecuatorial'),
('Guinesa','Guinea'),
('Guyanesa','Guyana'),
('Haitiana','Haiti'),
('Holandesa','Holanda'),
('Hondureña','Honduras'),
('Hungara','Hungria'),
('India','India'),
('Indonesa','Indonesia'),
('Inglesa','Inglaterra'),
('Iraki','Irak'),
('Irani','Iran'),
('Irlandesa','Irlanda'),
('Islandesa','Islandia'),
('Israeli','Israel'),
('Italiana','Italia'),
('Jamaiquina','Jamaica'),
('Japonesa','Japon'),
('Jordana','Jordania'),
('Katensa','Katar'),
('Keniana','Kenia'),
('Kuwaiti','Kwait'),
('Laosiana','Laos'),
('Leonesa','Sierra leona'),
('Lesothensa','Lesotho'),
('Letonesa','Letonia'),
('Libanesa','Libano'),
('Liberiana','Liberia'),
('Libeña','Libia'),
('Liechtenstein','Liechtenstein'),
('Lituana','Lituania'),
('Luxemburgo','Luxemburgo'),
('Madagascar','Madagascar'),
('Malaca','Fede. de Malasia'),
('Malawi','Malawi'),
('Maldivas','Maldivas'),
('Mali','Mali'),
('Maltesa','Malta'),
('Marfilesa','Costa de Marfil'),
('Marroqui','Marruecos'),
('Mauricio','Mauricio'),
('Mauritana','Mauritania'),
('Mexicana','México'),
('Monaco','Monaco'),
('Mongolesa','Mongolia'),
('Nauru','Nauru'),
('Neozelandesa','Nueva Zelandia'),
('Nepalesa','Nepal'),
('Nicaraguense','Nicaragua'),
('Nigerana','Niger'),
('Nigeriana','Nigeria'),
('Norcoreana','Corea del Norte'),
('Norirlandesa','Irlanda del norte'),
('Norteamericana','Estados unidos'),
('Noruega','Noruega'),
('Omana','Oman'),
('Pakistani','Pakistan'),
('Panameña','Panama'),
('Paraguaya','Paraguay'),
('Peruana','Peru'),
('Polaca','Polonia'),
('Portoriqueña','Puerto Rico'),
('Portuguesa','Portugal'),
('Rhodesiana','Rhodesia'),
('Ruanda','Ruanda'),
('Rumana','Rumania'),
('Rusa','Russia'),
('Salvadoreña','El Salvador'),
('Samoa Occidental','Samoa Occidental'),
('San marino','San Marino'),
('Saudi','Arabia Saudita'),
('Senegalesa','Senegal'),
('Sikkim','Sikkim'),
('Singapur','Singapur'),
('Siria','Siria'),
('Somalia','Somalia'),
('Sovietica','Union Sovietica'),
('Sri Lanka','Sri Lanka (Ceylan) '),
('Suazilandesa','Suazilandia'),
('Sudafricana','Sudafrica'),
('Sudanesa','Sudan'),
('Sueca','Suecia'),
('Suiza','Suiza'),
('Surcoreana','Corea del Sur'),
('Tailandesa','Tailandia'),
('Tanzana','Tanzania'),
('Tonga','Tonga'),
('Tongo','Tongo'),
('Trinidad y Tobago','Trinidad y Tobago'),
('Tunecina','Tunez'),
('Turca','Turquia'),
('Ugandesa','Uganda'),
('Uruguaya','Uruguay'),
('Vaticano','Vaticano'),
('Vietnamita','Vietnam'),
('Yemen Rep Arabe','Yemen Rep. Arabe'),
('Yemen Rep Dem','Yemen Rep. Dem'),
('Yugoslava','Yugoslavia'),
('Zaire','Zaire');

INSERT INTO zonas_horarias (zona, lugar)
VALUES (-11, 'Samoa'),
       (-10, 'Hawaii'),
       (-9, 'Anchorage, Juneau'),
       (-8, 'Seattle, San Francisco, Los Angeles'),
       (-7, 'Edmonton, Denver, Phoenix'),
       (-6, 'Chicago, Houston, Mexico'),
       (-5, 'New York, Miami, La Habana, Puerto Principe'),
       (-4, 'Santo Domingo, Caracas, Asuncion, Santiago de Chile'),
       (-3, 'Brasilia, Rio De Janeiro, Montevideo, Buenos Aires'),
       (-2, 'Recife'),
       (-1, 'Azores'),
       (0, 'Londres, Dublín, Lisboa, Casablanca, Dakar, Accra'),
       (1, 'Paris, Madrid, Roma, Berlín, Praga, Belgrado, Varsovia, Estocolmo'),
       (2, 'Helsinki, Minks, Bucarest, Estambul, Atenas, Beirut, Cairo'),
       (3, 'San Petersburgo, Moscow, Bagdad, Riad, Addis Abeba'),
       (4, 'Samara, Baku, Tbilisi, Dubai'),
       (5, 'Sheliabinsk, Karachi, Islamabad'),
       (6, 'Omsk, Tashkent, Dacca'),
       (7, 'Novosibirsk, Bangkok, Hanoi, Yakarta'),
       (8, 'Irkutsk, Lhasa, Beijing, Hong Kong, Kuala Lumpur'),
       (9, 'Tokyo, Seul'),
       (10, 'Vladivostok, Sydney, Melbourne'),
       (11, 'Noumea, Magaban'),
       (12, 'Wellington (Nueva Zelanda)');

INSERT INTO ligas (nombre, icono)
VALUES ('Arena 1', 0),
       ('Arena 2', 0),
       ('Arena 3', 0),
       ('Arena 4', 0),
       ('Arena 5', 0),
       ('Arena 6', 0),
       ('Arena 7', 0),
       ('Arena 8', 0),
       ('Arena 9', 0),
       ('Arena 10', 0),
       ('Arena 11', 0),
       ('Arena 12', 0),
       ('Combatientes I', 1),
       ('Combatientes II', 2),
       ('Combatientes III', 3),
       ('Maestros I', 4),
       ('Maestros II', 5),
       ('Maestros III', 6),
       ('Campeones', 7),
       ('Grandes Campeones', 8),
       ('Campeones Definitivos', 9);

INSERT INTO usuarios (nombre, password, correo, nacionalidad_id, zona_horaria_id, auth_key, verificado, activo)
VALUES ('admin', crypt('admin', gen_salt('bf', 13)), 'admin@dominio.com', 48, 13, 'WPBxyU4wBMiDlSOQiKlRXE-oEcg__VFA', null, true);

INSERT INTO permisos (nombre, descripcion)
VALUES ('verBackEndCalendario', 'Puede ver el calenario en backend'),
       ('programarEvento', 'Puede programar un evento nuevo'),
       ('elminarEvento', 'Puede eliminar un evento existente'),
       ('modificarEvento', 'Puede modificar un evento existente'),
       ('parametros', 'Puede modificar los parametros de la configuración web'),
       ('modificarRoles', 'Puede modificar los roles de los usuarios'),
       ('directo', 'Puede programar, activar y desactivar un directo'),
       ('usuarios', 'Puede eliminar, expulsar y quitar la expulsión de los usuarios'),
       ('solicitudes', 'Puede aceptar e invitar las solicitudes para entrar en el equipo y las de lucha'),
       ('loginBackEnd', 'Puede loguearse y acceder al backend');

INSERT INTO roles (nombre)
VALUES ('administrador'),
       ('Coach'),
       ('Líder'),
       ('Colíder'),
       ('Miembro'),
       ('En prueba');

INSERT INTO roles_permisos (rol_id, permiso_id)
VALUES (1, 1),
       (1, 2),
       (1, 3),
       (1, 4),
       (1, 5),
       (1, 6),
       (1, 7),
       (1, 8),
       (1, 9),
       (1, 10),
       (2, 1),
       (2, 2),
       (2, 3),
       (2, 4),
       (2, 9),
       (2, 10),
       (3, 1),
       (3, 2),
       (3, 3),
       (3, 4),
       (3, 8),
       (3, 9),
       (3, 10),
       (4, 1),
       (4, 2),
       (4, 3),
       (4, 4),
       (4, 9),
       (4, 10);

INSERT INTO usuarios_roles (usuario_id, rol_id)
VALUES (1, 1);

INSERT INTO evento_etiquetas (nombre, color)
VALUES ('Otros', 'gray'),
       ('Entrenamiento', 'blue'),
       ('Torneo', 'orange'),
       ('Liga', 'red'),
       ('Amistoso', 'green');

INSERT INTO config (mensaje_twitter, mensaje_unete_twitter, mensaje_whatsapp, mensaje_unete_whatsapp, usuario_twitch)
VALUES ('https://skeletons-trap.herokuapp.com/ ¿Te atréves a jugar contra nosotros? Somos Skeletons Trap',
        '¡Unete a nosotros! Es muy fácil, entra en el siguiente enlace para ello: https://skeletons-trap.herokuapp.com/site/unete',
        'https://skeletons-trap.herokuapp.com/ ¿Te atréves a jugar contra nosotros? Somos Skeletons Trap',
        '¡Unete a nosotros! Es muy fácil, entra en el siguiente enlace para ello: https://skeletons-trap.herokuapp.com/site/unete',
        'skeletonstraptv');

-- DATOS PRUEBAS
