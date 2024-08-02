<?php

namespace Domain\Service;

use PHPUnit\Framework\TestCase;
use Domain\Entity\Ticket;

class TicketGeneratorServiceTest extends TestCase
{
    public function testGenerateValidNumbersPerTicket(): void
    {
        $ticketGeneratorService = new TicketGeneratorService();
        $ticket = $ticketGeneratorService->generate(6);
        
        $this->assertInstanceOf(Ticket::class, $ticket);
        $numbers = $ticket->getNumbers();
        $this->assertCount(6, $numbers);
        $this->assertGreaterThanOrEqual(1, min($numbers));
        $this->assertLessThanOrEqual(60, max($numbers));
        $this->assertContainsOnly('integer', $numbers);
        $this->assertEquals(array_unique($numbers), $numbers); 
    }

    public function testGenerateTickets(): void
    {
        $ticketGeneratorService = new TicketGeneratorService();
        $tickets = $ticketGeneratorService->generateTickets(3, 6);
        
        $this->assertCount(3, $tickets);
        foreach ($tickets as $ticket) {
            $this->assertInstanceOf(Ticket::class, $ticket);
            $numbers = $ticket->getNumbers();
            $this->assertCount(6, $numbers);
            $this->assertGreaterThanOrEqual(1, min($numbers));
            $this->assertLessThanOrEqual(60, max($numbers));
            $this->assertContainsOnly('integer', $numbers);
            $this->assertEquals(array_unique($numbers), $numbers); 
        }
    }
}
