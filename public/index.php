<?php

require_once __DIR__ . '/../src/Database/Database.php';
require_once __DIR__ . '/../src/Models/Carne.php';
require_once __DIR__ . '/../src/Services/CarneService.php';
require_once __DIR__ . '/../src/Controllers/CarneController.php';

use App\Controllers\CarneController;
use App\Database\Database;
use App\Models\Carne;
use App\Services\CarneService;

// Conecte-se ao banco de dados
$pdo = Database::connect();

// Inicialize os objetos necessários
$carneModel = new Carne($pdo);
$carneService = new CarneService($carneModel);  // Crie uma instância de CarneService
$carneController = new CarneController($carneService);  // Passe CarneService para o controller

// Roteamento simples baseado na URL e no método HTTP
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($requestUri === '/carne' && $requestMethod === 'POST') {
    $carneController->create();
} elseif (preg_match('/\/carne\/(\d+)/', $requestUri, $matches) && $requestMethod === 'GET') {  // Corrigido para capturar o ID
    if (isset($matches[1])) {
        $carneId = $matches[1];  // Corretamente extrai o ID do carnê da URL
        $carneController->getParcelas($carneId);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'ID do carnê não especificado']);
        return;
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Recurso não encontrado']);
}
