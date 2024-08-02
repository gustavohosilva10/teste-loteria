<?php

namespace Application;

use Domain\Service\TicketGeneratorService;
use Domain\Entity\Ticket;

class GenerateMultipleTickets
{
    private TicketGeneratorService $ticketGeneratorService;

    public function __construct(TicketGeneratorService $ticketGeneratorService)
    {
        $this->ticketGeneratorService = $ticketGeneratorService;
    }

    public function execute(int $quantity, int $numbersPerTicket, string $cpf): array
    {
        if ($quantity <= 0 || $quantity > 50) {
            throw new \InvalidArgumentException('Quantidade de bilhetes deve ser entre 1 e 50.');
        }

        if ($numbersPerTicket < 6 || $numbersPerTicket > 10) {
            throw new \InvalidArgumentException('Número de dezenas deve ser entre 6 e 10.');
        }

        $tickets = $this->ticketGeneratorService->generateTickets($quantity, $numbersPerTicket);
        foreach ($tickets as $ticket) {
            $this->saveTicket($ticket, $cpf);
        }

        return array_map(fn($t) => $t->getNumbers(), $tickets);
    }

    private function saveTicket(Ticket $ticket, string $cpf): void
    {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO tickets (numbers, cpf) VALUES (:numbers, :cpf)');
        $stmt->execute([
            'numbers' => json_encode($ticket->getNumbers()),
            'cpf' => $cpf
        ]);
    }
}
