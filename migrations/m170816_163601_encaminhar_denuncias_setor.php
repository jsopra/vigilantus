<?php

use yii\db\Migration;

class m170816_163601_encaminhar_denuncias_setor extends Migration
{
    public function safeUp()
    {
        $this->addColumn('ocorrencias', 'setor_id', 'integer references setores(id)');

    	$this->execute("
    		UPDATE ocorrencias
            SET setor_id = (
                SELECT id
                FROM setores
                WHERE
                    padrao_ocorrencias = TRUE AND
                    cliente_id = ocorrencias.cliente_id
            )
    	");
    }

    public function safeDown()
    {
        $this->dropColumn('ocorrencias', 'setor_id');
    }
}
