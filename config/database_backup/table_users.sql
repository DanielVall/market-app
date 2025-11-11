-- config/table_users.sql
-- SCHEMA: countries, regions, cities, users + seeds

-- 1) Countries
CREATE TABLE IF NOT EXISTS countries (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  abbrev VARCHAR(20),
  code VARCHAR(20),
  created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- 2) Regions
CREATE TABLE IF NOT EXISTS regions (
  id BIGSERIAL PRIMARY KEY,
  country_id BIGINT NOT NULL REFERENCES countries(id) ON DELETE CASCADE,
  name VARCHAR(100) NOT NULL,
  abbrev VARCHAR(20),
  code VARCHAR(20),
  created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT now(),
  UNIQUE(country_id, name)
);

-- 3) Cities
CREATE TABLE IF NOT EXISTS cities (
  id BIGSERIAL PRIMARY KEY,
  region_id BIGINT NOT NULL REFERENCES regions(id) ON DELETE CASCADE,
  name VARCHAR(100) NOT NULL,
  abbrev VARCHAR(20),
  code VARCHAR(20),
  created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT now(),
  UNIQUE(region_id, name)
);

-- 4) Users (Actualización con la columna url_photo)
CREATE TABLE IF NOT EXISTS users (
  id BIGSERIAL PRIMARY KEY,
  firstname VARCHAR(30) NOT NULL,
  lastname VARCHAR(30) NOT NULL,
  mobile_number VARCHAR(20) NOT NULL,
  ide_number VARCHAR(30) UNIQUE,
  address TEXT,
  birthday DATE,
  email VARCHAR(200) NOT NULL UNIQUE,
  password TEXT NOT NULL,
  status BOOLEAN NOT NULL DEFAULT TRUE,
  city_birth_id BIGINT REFERENCES cities(id),
  city_issue_id BIGINT REFERENCES cities(id),
  created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NOT NULL DEFAULT now(),
  deleted_at TIMESTAMPTZ NULL,
  url_photo VARCHAR(255) DEFAULT 'photos/user_default.png' -- Nueva columna para la foto de perfil
);
