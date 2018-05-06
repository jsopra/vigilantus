<?php

use yii\db\Migration;

class m171005_213210_campo_bairroId_caso_doenca extends Migration
{
    public function safeUp()
    {
   		$this->addColumn('casos_doencas', 'bairro_id', 'integer references bairros(id)');
    }

    public function safeDown()
    {
        echo "m171005_213210_campo_bairroId_caso_doenca cannot be reverted.\n";
        return false;
    }
}
