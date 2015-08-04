<?php

use yii\db\Migration;

class m150720_231134_removendo_not_null_ocorrencia extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE ocorrencias ALTER COLUMN bairro_id DROP NOT NULL");
    }

    public function safeDown()
    {
        echo "m150720_231134_removendo_not_null_ocorrencia cannot be reverted.\n";
        return false;
    }
}
