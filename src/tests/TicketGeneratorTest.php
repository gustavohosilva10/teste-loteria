<?php

namespace Domain\Service;

use PHPUnit\Framework\TestCase;
use Domain\Entity\Ticket;

class TicketGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $ticketGenerator = new TicketGenerator();
        $ticket = $ticketGenerator->generate();
        
        $this->assertInstanceOf(Ticket::class, $ticket);
        $numbers = $ticket->getNumbers();
        $this->assertCount(6, $numbers);
        $this->assertGreaterThanOrEqual(1, min($numbers));
        $this->assertLessThanOrEqual(60, max($numbers));
        $this->assertContainsOnly('integer', $numbers);
        $this->assertEquals(array_unique($numbers), $numbers);
    }
}
