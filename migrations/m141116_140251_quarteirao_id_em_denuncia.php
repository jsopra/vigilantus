<?php

use yii\db\Migration;

class m141116_140251_quarteirao_id_em_denuncia extends Migration
{
    public function safeUp()
    {
    	$this->addColumn('denuncias', 'bairro_quarteirao_id', 'integer references bairro_quarteiroes(id)');
    }

    public function safeDown()
    {
        echo "m141116_140251_quarteirao_id_em_denuncia cannot be reverted.\n";
        return false;
    }
}
