<?php

namespace App\Services;

use App\Models\Carne;
use DateTime;

/**
 * Serviço para gerenciar as operações relacionadas a carnês.
 */
class CarneService
{
    private $carneModel;

    /**
     * Construtor que inicializa o modelo Carne.
     *
     * @param Carne $carneModel Modelo para operações de banco de dados relacionadas a carnês.
     */
    public function __construct(Carne $carneModel)
    {
        $this->carneModel = $carneModel;
    }

    /**
     * Cria um novo carnê e suas parcelas com base nos dados fornecidos.
     *
     * @param array $data Dados para criação do carnê.
     * @return array Dados do carnê criado, incluindo ID e parcelas.
     * @throws \Exception Se dados obrigatórios estiverem faltando.
     */
    public function createCarne(array $data)
    {
        $this->validateData($data);
        
        $valorTotal = (float) $data['valor_total'];
        $qtdParcelas = (int) $data['qtd_parcelas'];
        $dataPrimeiroVencimento = new DateTime($data['data_primeiro_vencimento']);
        $periodicidade = $data['periodicidade'];
        $valorEntrada = isset($data['valor_entrada']) ? (float) $data['valor_entrada'] : 0;

        $parcelas = $this->generateParcelas($valorTotal, $qtdParcelas, $dataPrimeiroVencimento, $periodicidade, $valorEntrada);

        $carneId = $this->carneModel->create($data);
        $this->carneModel->createParcelas($carneId, $parcelas);

        return [
            'id' => $carneId,
            'total' => $valorTotal,
            'valor_entrada' => $valorEntrada,
            'parcelas' => $parcelas
        ];
    }

    /**
     * Valida se todos os campos necessários estão presentes nos dados.
     *
     * @param array $data Dados do carnê.
     * @throws \Exception Se algum campo obrigatório estiver faltando.
     */
    private function validateData($data)
    {
        if (!isset($data['valor_total'], $data['qtd_parcelas'], $data['data_primeiro_vencimento'], $data['periodicidade'])) {
            throw new \Exception('Missing required fields');
        }
    }

    /**
     * Gera as parcelas do carnê com base nos dados fornecidos.
     *
     * @param float $valorTotal Valor total do carnê.
     * @param int $qtdParcelas Quantidade de parcelas.
     * @param DateTime $dataPrimeiroVencimento Data do primeiro vencimento.
     * @param string $periodicidade Periodicidade das parcelas.
     * @param float $valorEntrada Valor da entrada, se aplicável.
     * @return array Lista de parcelas geradas.
     */
    private function generateParcelas($valorTotal, $qtdParcelas, $dataPrimeiroVencimento, $periodicidade, $valorEntrada)
    {
        $parcelas = [];
        $valorPorParcela = ($valorTotal - $valorEntrada) / $qtdParcelas;
        
        for ($i = 1; $i <= $qtdParcelas; $i++) {
            $dataVencimento = clone $dataPrimeiroVencimento;
            $dataVencimento->modify('+' . ($i - 1) . ' ' . $this->getPeriodicidadeModifier($periodicidade));

            $parcelas[] = [
                'numero' => $i,
                'data_vencimento' => $dataVencimento->format('Y-m-d'),
                'valor' => round($valorPorParcela, 2),
                'entrada' => false
            ];
        }

        if ($valorEntrada > 0) {
            array_unshift($parcelas, [
                'numero' => 0,
                'data_vencimento' => (new DateTime())->format('Y-m-d'),
                'valor' => round($valorEntrada, 2),
                'entrada' => true
            ]);
        }

        return $parcelas;
    }

    /**
     * Retorna o modificador de periodicidade adequado para cálculos de data.
     *
     * @param string $periodicidade Periodicidade (mensal, semanal, etc.).
     * @return string Modificador de periodicidade para uso no método DateTime::modify.
     */
    private function getPeriodicidadeModifier($periodicidade)
    {
        switch ($periodicidade) {
            case 'mensal':
                return 'month';
            case 'semanal':
                return 'week';
            default:
                throw new \Exception('Invalid periodicity');
        }
    }

    /**
     * Busca as parcelas de um carnê específico pelo seu ID.
     *
     * @param int $carneId O ID do carnê.
     * @return array Lista das parcelas do carnê.
     * @throws \Exception Se o carnê não for encontrado.
     */
    public function getParcelas($carneId)
    {
        $parcelas = $this->carneModel->findParcelas($carneId);
        if (!$parcelas) {
            throw new \Exception("Carnê não encontrado.");
        }
        return $parcelas;
    }

}
