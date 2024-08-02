<?php

namespace Controller;

use Application\GenerateTicket;
use Application\GenerateMultipleTickets;
use PDO;

class TicketController
{
    private GenerateTicket $generateTicket;
    private GenerateMultipleTickets $generateMultipleTickets;
    private PDO $pdo;

    public function __construct(GenerateTicket $generateTicket, GenerateMultipleTickets $generateMultipleTickets, PDO $pdo)
    {
        $this->generateTicket = $generateTicket;
        $this->generateMultipleTickets = $generateMultipleTickets;
        $this->pdo = $pdo;
    }

    public function generateSingleTicketWinner(): void
    {
        header('Content-Type: application/json');
        echo json_encode($this->generateTicket->execute());
    }

    public function generateMultipleTickets(): void
    {
        header('Content-Type: application/json');
        $quantity = $_GET['quantity'] ?? 1;
        $numbersPerTicket = $_GET['numbers_per_ticket'] ?? 6;
        $cpf = $_GET['cpf'] ?? '';

        if (!is_numeric($quantity) || !is_numeric($numbersPerTicket) || empty($cpf)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Parâmetros inválidos.',
            ]);
            return;
        }

        $quantity = (int)$quantity;
        $numbersPerTicket = (int)$numbersPerTicket;

        try {
            $result = $this->generateMultipleTickets->execute($quantity, $numbersPerTicket, $cpf);
            echo json_encode($result);
        } catch (\InvalidArgumentException $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function checkTicketsAgainstWinning(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; 
        $offset = ($page - 1) * $limit;

        $stmt = $this->pdo->query('SELECT * FROM winning_ticket ORDER BY created_at DESC LIMIT 1');
        $winningTicket = $stmt->fetch();
        if (!$winningTicket) {
            echo json_encode(['error' => 'Bilhete premiado não encontrado']);
            return;
        }
        $winningNumbers = json_decode($winningTicket['numbers'], true);

        $stmt = $this->pdo->prepare('SELECT * FROM tickets LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $tickets = $stmt->fetchAll();

        $results = [];
        foreach ($tickets as $ticket) {
            $numbers = json_decode($ticket['numbers'], true);
            $matchingNumbers = array_intersect($numbers, $winningNumbers);
            $results[] = [
                'id' => $ticket['id'],
                'numbers' => implode(', ', $numbers),
                'result' => (count($matchingNumbers) > 0 ? 'Números Sorteados: ' . implode(', ', $matchingNumbers) : 'Nenhum número sorteado')
            ];
        }

        $stmt = $this->pdo->query('SELECT COUNT(*) AS total FROM tickets');
        $total = $stmt->fetchColumn();
        $totalPages = ceil($total / $limit);

        header('Content-Type: application/json');
        echo json_encode([
            'page' => $page,
            'total_pages' => $totalPages,
            'tickets' => $results
        ]);
    }
}
