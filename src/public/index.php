<?php

require '../vendor/autoload.php';
require '../db.php'; 

use Controller\TicketController;
use Application\GenerateTicket;
use Application\GenerateMultipleTickets;
use Domain\Service\TicketGenerator;
use Domain\Service\TicketGeneratorService;

$pdo = $pdo; 

$ticketGenerator = new TicketGenerator();
$generateTicket = new GenerateTicket($ticketGenerator);

$ticketGeneratorService = new TicketGeneratorService();
$generateMultipleTickets = new GenerateMultipleTickets($ticketGeneratorService);

$controller = new TicketController($generateTicket, $generateMultipleTickets, $pdo);

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?? '';
parse_str($queryString, $queryParams);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($requestUri) {
        case '/generate-ticket':
            $controller->generateSingleTicketWinner();
            break;
        case '/generate-multiple-tickets':
            $controller->generateMultipleTickets();
            break;
        case '/check-tickets':
            $_GET = $queryParams;
            $controller->checkTicketsAgainstWinning();
            break;
        default:
            http_response_code(404);
            exit(json_encode(['error' => 'Endpoint não encontrado']));
    }
} else {
    http_response_code(405);
    exit(json_encode(['error' => 'Método não permitido']));
}
