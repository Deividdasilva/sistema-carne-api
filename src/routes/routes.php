<?php

use App\Controllers\CarneController;
use App\Database\Database;
use App\Models\Carne;

try {
    $pdo = Database::connect();
    
    $carneModel = new Carne($pdo);
    $carneController = new CarneController($carneModel);

    // Define rotas
    $app->post('/carne', [$carneController, 'create']);
    $app->get('/carne/{id}', [$carneController, 'getParcelas']);

} catch (Exception $e) {
    error_log($e->getMessage());
    $app->get('/{route:.*}', function($request, $response) use ($e) {
        return $response->withStatus(500)->write('Internal Server Error: ' . $e->getMessage());
    });
}
