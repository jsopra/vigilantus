<?php

use yii\db\Migration;

class m151207_212450_cadastra_tipos_problemas extends Migration
{
    public function safeUp()
    {
        $problemas = [
            'Abelhas e vespas',
            'Animais em perímetro urbano',
            'Aranhas, escorpiões e Chilopodos',
            'Boca de lobo',
            'Caixas de água, tanques ...',
            'Calhas',
            'Canos de parabólica',
            'Construções abandonadas',
            'Corujas',
            'Formigas',
            'Lages com água',
            'Lesmas e caracóis',
            'Lixo',
            'Morcegos',
            'Moscas e mosquitos',
            'Percevejos',
            'Piscina, lago artificial ...',
            'Pneus',
            'Pulgas, piolhos e bichos-de-pé',
            'Roedores',
            'Saneamento (fossa aberta, riacho ...)',
            'Serpentes',
            'Sucatas, peças, ...',
            'Taturanas e outras lagartas',
            'Terreno abandonado',
        ];

        $command = $this->db->createCommand(
            "SELECT m.id AS municipio_id, c.id AS cliente_id
            FROM clientes c
            INNER JOIN municipios m ON c.municipio_id = m.id
            WHERE c.id NOT IN (SELECT cliente_id FROM ocorrencia_tipos_problemas);"
        );
        $reader = $command->query();
        while ($row = $reader->read()) {
            $this->batchInsert(
                'ocorrencia_tipos_problemas',
                [
                    'nome',
                    'ativo',
                    'inserido_por',
                    'cliente_id',
                ],
                array_map(
                    function($problema) use ($row) {
                        return [
                            'nome' => $problema,
                            'ativo' => true,
                            'inserido_por' => 1,
                            'cliente_id' => $row['cliente_id'],
                        ];
                    },
                    $problemas
                )
            );
        }
    }

    public function safeDown()
    {
        echo "m151207_212450_cadastra_tipos_problemas cannot be reverted.\n";
        return false;
    }
}
