<?php

use yii\db\Migration;

class m141119_014249_rotulo_cliente extends Migration
{
    public function safeUp()
    {
    	$this->addColumn('clientes', 'rotulo', 'varchar');
    }

    public function safeDown()
    {
        echo "m141119_014249_rotulo_cliente cannot be reverted.\n";
        return false;
    }
}
