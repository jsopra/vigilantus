<?php

use yii\db\Migration;

class m140610_195846_alterando_tipo_campo_bairro_quarteirao extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('bairro_quarteiroes', 'numero_quarteirao', 'varchar');
        $this->alterColumn('bairro_quarteiroes', 'numero_quarteirao_2', 'varchar');
    }

    public function safeDown()
    {
        echo "m140610_195846_alterando_tipo_campo_bairro_quarteirao cannot be reverted.\n";
        return false;
    }
}
