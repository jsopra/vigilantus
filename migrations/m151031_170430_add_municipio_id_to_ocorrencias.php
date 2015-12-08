<?php

use yii\db\Expression;
use yii\db\Migration;

class m151031_170430_add_municipio_id_to_ocorrencias extends Migration
{
    public function safeUp()
    {
        $this->addColumn('ocorrencias', 'municipio_id', 'integer');
        $this->update(
            'ocorrencias',
            ['municipio_id' => new Expression('(SELECT municipio_id FROM clientes WHERE clientes.id = cliente_id)')]
        );
        $this->db->pdo->query('ALTER TABLE ocorrencias ALTER COLUMN municipio_id SET NOT NULL');
    }

    public function safeDown()
    {
        $this->dropColumn('ocorrencias', 'municipio_id');
    }
}
