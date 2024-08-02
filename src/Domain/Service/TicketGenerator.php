<?php

namespace Domain\Service;

use Domain\Entity\Ticket;

class TicketGenerator
{
    public function generate(): Ticket
    {
        $numbers = range(1, 60);
        shuffle($numbers);
        $selectedNumbers = array_slice($numbers, 0, 6);
        sort($selectedNumbers);
        
        return new Ticket($selectedNumbers);
    }
}
