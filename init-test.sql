CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numbers JSON NOT NULL,
    cpf VARCHAR(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS winning_ticket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numbers JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO tickets (numbers, cpf) VALUES
('[1, 2, 3, 4, 5, 6]', '12345678901'),
('[7, 8, 9, 10, 11, 12]', '10987654321');

INSERT INTO winning_ticket (numbers) VALUES
('[5, 6]');
