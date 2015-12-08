<?php

use yii\db\Migration;

class m151121_010000_adiciona_clientes_todos_municipios extends Migration
{
    public function safeUp()
    {
        $this->db->pdo->query("
            INSERT INTO clientes
            (municipio_id, nome_contato, telefone_contato, departamento)
            SELECT id, '', '', '' FROM municipios
            WHERE id NOT IN (SELECT municipio_id FROM clientes)"
        );
    }

    public function safeDown()
    {
        echo "m151205_160857_adiciona_clientes_todos_municipios cannot be reverted.\n";
        return false;
    }
}
