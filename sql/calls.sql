

/*
    Name: Soham Patel
    Date: September 13, 2023
    Course Code: INFT2100
*/
DROP SEQUENCE IF EXISTS call_id_seq
CASCADE;

CREATE SEQUENCE call_id_seq
START 100;

CREATE TABLE calls
(
    id INT PRIMARY KEY DEFAULT nextval('call_id_seq'),
    time_to_call TIMESTAMP,
    client_id INT,
    notes VARCHAR(1024),
    FOREIGN KEY (client_id) REFERENCES clients(ID)
);

INSERT INTO calls
    (time_to_call,notes,client_id)
VALUES
    (
        '2023-10-16 11:40:00',
        'first call',
        5000
);
SELECT * FROM calls;