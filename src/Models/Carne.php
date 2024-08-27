<?php

namespace App\Models;

use PDO;

/**
 * Modelo para a gestão de carnês e parcelas.
 */
class Carne
{
    private $db;

    /**
     * Construtor que recebe uma instância de PDO.
     *
     * @param PDO $db Instância de conexão com o banco de dados.
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Cria um novo carnê no banco de dados.
     *
     * @param array $data Dados do carnê.
     * @return int Retorna o ID do carnê criado.
     */
    public function create($data)
    {
        $stmt = $this->db->prepare('INSERT INTO carne (valor_total, qtd_parcelas, data_primeiro_vencimento, periodicidade, valor_entrada) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            $data['valor_total'],
            $data['qtd_parcelas'],
            $data['data_primeiro_vencimento'],
            $data['periodicidade'],
            $data['valor_entrada'] ?? 0
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Cria parcelas para um carnê especificado.
     *
     * @param int $carneId ID do carnê.
     * @param array $parcelas Dados das parcelas.
     */
    public function createParcelas($carneId, $parcelas)
    {
        $stmt = $this->db->prepare('INSERT INTO parcelas (carne_id, numero, data_vencimento, valor, entrada) VALUES (?, ?, ?, ?, ?)');
        foreach ($parcelas as $parcela) {
            $stmt->execute([
                $carneId,
                $parcela['numero'],
                $parcela['data_vencimento'],
                $parcela['valor'],
                $parcela['entrada'] ? 1 : 0
            ]);
        }
    }

    /**
     * Busca todas as parcelas de um carnê específico.
     *
     * @param int $carneId ID do carnê.
     * @return array Lista de parcelas.
     */
    public function findParcelas($carneId)
    {
        $stmt = $this->db->prepare('SELECT * FROM parcelas WHERE carne_id = ?');
        $stmt->execute([$carneId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
