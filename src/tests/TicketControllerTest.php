<?php

use PHPUnit\Framework\TestCase;
use Controller\TicketController;
use Application\GenerateTicket;
use Application\GenerateMultipleTickets;
#use PDO;

class TicketControllerTest extends TestCase
{
    private PDO $pdo;
    private TicketController $controller;
    private $generateTicket;
    private $generateMultipleTickets;

    protected function setUp(): void
    {
        $this->pdo = new PDO("mysql:host=test-db;dbname=test_database;charset=utf8", 'root', 'root');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->generateTicket = $this->createMock(GenerateTicket::class);
        $this->generateMultipleTickets = $this->createMock(GenerateMultipleTickets::class);

        $this->controller = new TicketController($this->generateTicket, $this->generateMultipleTickets, $this->pdo);
    }

    public function testGenerateSingleTicketWinner(): void
    {
        $this->generateTicket->method('execute')->willReturn(['ticket' => '123456']);

        ob_start();
        $this->controller->generateSingleTicketWinner();
        $response = ob_get_clean();

        $this->assertJson($response);
        $this->assertStringContainsString('123456', $response);
    }

    public function testGenerateMultipleTickets(): void
    {
        $this->generateMultipleTickets->method('execute')->willReturn(['tickets' => ['123456', '654321']]);

        $_GET['quantity'] = 2;
        $_GET['numbers_per_ticket'] = 6;
        $_GET['cpf'] = '12345678901';

        ob_start();
        $this->controller->generateMultipleTickets();
        $response = ob_get_clean();

        $this->assertJson($response);
        $this->assertStringContainsString('123456', $response);
        $this->assertStringContainsString('654321', $response);
    }

    public function testCheckTicketsAgainstWinning(): void
    {
        $this->pdo->exec('INSERT INTO winning_ticket (numbers, created_at) VALUES ("[1, 2, 3, 4, 5, 6]", NOW())');
    
        $this->pdo->exec('INSERT INTO tickets (numbers, cpf) VALUES ("[1, 2, 7, 8, 9, 10]", "12345678901")');
        $this->pdo->exec('INSERT INTO tickets (numbers, cpf) VALUES ("[3, 4, 5, 6, 7, 8]", "10987654321")');
    
        $_GET['page'] = 1;
    
        ob_start();
        $this->controller->checkTicketsAgainstWinning();
        $response = ob_get_clean();
    
        $responseArray = json_decode($response, true);
    
        $this->assertArrayHasKey('tickets', $responseArray);
    
        $expectedResults = [
            ['id' => 1, 'numbers' => '1, 2, 3, 4, 5, 6', 'result' => 'Números Sorteados: 1, 2, 3, 4, 5, 6'],
            ['id' => 2, 'numbers' => '7, 8, 9, 10, 11, 12', 'result' => 'Nenhum número sorteado'],
            ['id' => 3, 'numbers' => '1, 2, 7, 8, 9, 10', 'result' => 'Números Sorteados: 1, 2'],
            ['id' => 4, 'numbers' => '3, 4, 5, 6, 7, 8', 'result' => 'Números Sorteados: 3, 4, 5, 6']
        ];
    
        foreach ($expectedResults as $expected) {
            $this->assertContains($expected, $responseArray['tickets']);
        }
    }
    

}
