<?php

namespace Domain\Service;

use Domain\Entity\Ticket;

class TicketGeneratorService
{
    public function generate(int $numbersPerTicket): Ticket
    {
        if ($numbersPerTicket < 6 || $numbersPerTicket > 10) {
            throw new \InvalidArgumentException('Número de dezenas deve ser entre 6 e 10.');
        }

        $numbers = range(1, 60);
        shuffle($numbers);
        $selectedNumbers = array_slice($numbers, 0, $numbersPerTicket);
        sort($selectedNumbers);

        return new Ticket($selectedNumbers);
    }

    public function generateTickets(int $quantity, int $numbersPerTicket): array
    {
        if ($quantity <= 0 || $quantity > 50) {
            throw new \InvalidArgumentException('Quantidade de bilhetes deve ser entre 1 e 50.');
        }

        if ($numbersPerTicket < 6 || $numbersPerTicket > 10) {
            throw new \InvalidArgumentException('Número de dezenas deve ser entre 6 e 10.');
        }

        $tickets = [];
        for ($i = 0; $i < $quantity; $i++) {
            $tickets[] = $this->generate($numbersPerTicket);
        }

        return $tickets;
    }
}
