<?php

use yii\db\Migration;

class m180805_212520_alterar_coluna_nome_errado_visita extends Migration
{
    public function safeUp()
    {
    	$this->execute("ALTER TABLE visita_imoveis RENAME depositos_elimidados TO depositos_eliminados");
    }

    public function safeDown()
    {
        echo "m180805_212520_alterar_coluna_nome_errado_visita cannot be reverted.\n";
        return false;
    }
}
