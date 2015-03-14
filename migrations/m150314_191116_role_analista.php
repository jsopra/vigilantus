<?php

use yii\db\Migration;

class m150314_191116_role_analista extends Migration
{
    public function safeUp()
    {
        $this->insert('usuario_roles', array('nome' => 'Analista'));
    }

    public function safeDown()
    {
        echo "m150314_191116_role_analista cannot be reverted.\n";
        return false;
    }
}
