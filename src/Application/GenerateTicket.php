<?php

namespace Application;

use Domain\Service\TicketGenerator;
use Domain\Entity\Ticket;

class GenerateTicket
{
    private TicketGenerator $ticketGenerator;

    public function __construct(TicketGenerator $ticketGenerator)
    {
        $this->ticketGenerator = $ticketGenerator;
    }

    public function execute(): array
    {
        $ticket = $this->ticketGenerator->generate();
        $this->saveTicket($ticket);
        return $ticket->getNumbers(); 
    }

    private function saveTicket(Ticket $ticket): void
    {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO winning_ticket (numbers, created_at) VALUES (:numbers, NOW())');
        $stmt->execute(['numbers' => json_encode($ticket->getNumbers())]);
    }
}
