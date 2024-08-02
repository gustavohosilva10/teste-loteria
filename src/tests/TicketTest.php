<?php

namespace Domain\Entity;

use PHPUnit\Framework\TestCase;

class TicketTest extends TestCase
{
    public function testTicketCreation(): void
    {
        $numbers = [1, 2, 3, 4, 5, 6];
        $ticket = new Ticket($numbers);
        
        $this->assertSame($numbers, $ticket->getNumbers());
    }
}
