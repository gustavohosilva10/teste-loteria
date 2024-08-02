CREATE TABLE IF NOT EXISTS winning_ticket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numbers JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numbers JSON NOT NULL,
    cpf VARCHAR(11) NOT NULL,
    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
