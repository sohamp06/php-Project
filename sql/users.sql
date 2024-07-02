--         Name: Soham Patel
--         Date: 13/09/2023
--         Course code : INFT2100


CREATE EXTENSION IF NOT EXISTS pgcrypto;


DROP SEQUENCE IF EXISTS users_id_seq  CASCADE;
CREATE SEQUENCE users_id_seq  START 1000;

DROP TABLE IF EXISTS users;
CREATE TABLE users (

    ID INT PRIMARY KEY DEFAULT nextval('users_id_seq'),
    EmailAddress VARCHAR(255) UNIQUE,
    Password VARCHAR(255) NOT NULL,
    FirstName VARCHAR(128) NOT NULL,
    LastName VARCHAR(128) NOT NULL,
    CreatedTime TIMESTAMP,
    LastLoggedIn TIMESTAMP,
    PhoneExtension VARCHAR(128),
    UserType VARCHAR(2)

);

INSERT INTO users (EmailAddress, Password, FirstName, LastName, CreatedTime, LastLoggedIn, PhoneExtension, UserType)
VALUES (
    'jdoe@dcmail.ca',
    crypt('password', gen_salt('bf')),
    'John',
    'Doe',
    '2023-09-05 19:10:25',
    '2023-10-24 23:09:00',
    '1211',
    'a'
    );

 