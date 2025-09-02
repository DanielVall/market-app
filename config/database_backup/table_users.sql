CREATE TABLE usuers(
	id BIGSERIAL PRIMARY KEY
	firstname VARCHAR(30) NOT NULL, 
	lastname VARCHAR(30) NOT NUL,
	mobile_number VARCHAR(20) NOT NULL,
	ide_number VARCHAR(15) NULL UNIQUE, 
	address TEXT NULL,
	birthday DATE NULL,
	email VARCHAR(200 NOT NULL UNIQUE
	password TEXT NOT NULL, 
	status BOOLEAN NOT NULL DEFAULT TRUE,	
	create_at TIMESTAMPTZ NOT NULL DEFAULT now(),
	updated_at TIMESTAMPTZ NOT NULL DEFAULT now(),
	deleted_at TIMESTAMPTZ NULL 
);

--insert into table users
INSERT INTO users (
	firstname,
	lastname,
	mobile_number,
	email,
	password
)
VALUES (
	'Daniel',
	'Vallejo',
	'3216084867',
	'daniel@gmail.com',
	'1234',
);