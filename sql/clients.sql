/*
    Name: Soham Patel
    Date: September 13, 2023
    Course Code: INFT2100
*/
DROP SEQUENCE IF EXISTS client_id_seq
CASCADE;

CREATE SEQUENCE client_id_seq
START 5000;

DROP TABLE IF EXISTS clients
CASCADE;
CREATE TABLE clients
(
    ID INT PRIMARY KEY DEFAULT nextval('client_id_seq'),
    EmailAddress VARCHAR(255) UNIQUE,
    FirstName VARCHAR(128),
    LastName VARCHAR(128),
    PhoneNumber VARCHAR(15),
    LogoPath VARCHAR(300),
    Extension VARCHAR(15),
    Sales_id INT,
    FOREIGN KEY(Sales_id) REFERENCES users(ID)
    
);

INSERT INTO clients
    (EmailAddress,FirstName,LastName, PhoneNumber,LogoPath, Extension, Sales_id)
VALUES(
        't@g.ca',
        'Sam',
        'Tom',
        '(641)456-8091',
        '',
        1,
        1000
);

SELECT *
FROM clients;