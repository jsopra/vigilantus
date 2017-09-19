<?php

use yii\db\Migration;

class m170919_130819_add_novos_modulos extends Migration
{
    public function safeUp()
    {
    	$this->insert('modulos', array(
    		'id' => 3,
            'nome' => 'Localização',
            'ativo' => TRUE,
        ));

        $this->insert('modulos', array(
    		'id' => 4,
            'nome' => 'Focos',
            'ativo' => TRUE,
        ));
    }

    public function safeDown()
    {
        echo "m170911_190954_add_novos_modulos cannot be reverted.\n";
        return false;
    }
}