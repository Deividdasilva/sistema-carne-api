<?php

namespace App\Controllers;

use App\Services\CarneService;

/**
 * Controlador para operações de API relacionadas a carnês.
 */
class CarneController
{
    private $carneService;

    /**
     * Construtor que recebe o serviço de Carnê.
     *
     * @param CarneService $carneService Serviço para gerenciar carnês.
     */
    public function __construct(CarneService $carneService)
    {
        $this->carneService = $carneService;
    }

    /**
     * Cria um carnê com base nos dados da requisição.
     */
    public function create()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        try {
            $result = $this->carneService->createCarne($data);
            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Recupera as parcelas de um carnê específico.
     *
     * @param string $id Identificador do carnê.
     */
    public function getParcelas($id)
    {
        try {
            $parcelas = $this->carneService->getParcelas($id);
            echo json_encode([
                'success' => true,
                'data' => $parcelas
            ]);
        } catch (\Exception $e) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
}
