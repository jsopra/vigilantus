<?php

use yii\db\Migration;

class m160809_173602_colunas_avaliacao_ocorrencia extends Migration
{
    public function safeUp()
    {
        $this->addColumn('ocorrencias', 'rating', 'float');
        $this->addColumn('ocorrencias', 'comentario_avaliacao', 'varchar');
        $this->addColumn('ocorrencias', 'avaliado_em', 'timestamp with time zone');

    }

    public function safeDown()
    {
        echo "m160809_173602_colunas_avaliacao_ocorrencia cannot be reverted.\n";
        return false;
    }
}
